<?PHP
Zend_Loader::loadClass("Smarty", PATH_LIBRARY_SMARTY);
Zend_Loader::loadClass("Zend_View_Interface");

abstract class ViewAbstract implements Zend_View_Interface {

	/**
	 * Экземпляр объекта шаблонизатора
	 *
	 * @var Smarty
	 */
	protected $_smarty = null;

	/**
	 * Параметры пользовательского запроса
	 *
	 * @var array
	 */
	protected $_requestParams = array();

	/**
	 * Имя шаблона для визуализации
	 *
	 * @var string
	 */
	protected $_template = null;

	/**
	 * Коструктор класса.
	 * В качестве параметра передается массив, содержащий имена контроллера, действия, языка и пользовательских параметров
	 *
	 * @param array $params
	 */
	public function __construct() {

		$params = Zend_Controller_Front::getInstance()->getRequest()->getUserParams();
		$params['controller'] = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
		$params['action'] = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

		$this->_requestParams = $params;

		$this->_initTemplateEngine();

		$this->init();

		$this->assign("user_params", $params);

		$this->assign("document_root", PATH_BASE);

		$this->_attachHelpers();
	}

	/**
	 * Функция дополнительной инициализации
	 *
	 */
	abstract protected function init();

	/**
	 * Инициализация шаблонизатора
	 *
	 */
	protected function _initTemplateEngine() {
		$this->_smarty = new Smarty();
		$this->_smarty->template_dir = PATH_TEMPLATES;
		$this->_smarty->compile_dir = PATH_TEMPLATES_COMPILED;
		$this->_smarty->config_dir = PATH_LANGUAGES . "/" . $this->_requestParams['language'] . "/";

		$this->_smarty->debugging = (boolean) SYSTEM_DEBUG;
		$this->_smarty->use_sub_dirs = true;

		$this->_smarty->register_function("getUrl", array($this, "getUrl"));
	}

	/**
	 * Перегрузочный метод для удобной передачи данных в шаблонизатор
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value) {
		$this->assign($name, $value);
	}

	public function __get($name) {
		return $this->_smarty->get_template_vars($name);
	}

	/**
	 * Вывод данных в браузер
	 *
	 */
	public function render($name) {

	}

	public function getEngine() {
		return $this->_smarty;
	}

	public function setScriptPath($path) {
		$this->_smarty->template_dir = $path;
	}

	public function getScriptPaths() {
		return $this->_smarty->template_dir;
	}

	public function addBasePath($path, $classPrefix = 'Zend_View') {

	}

	public function setBasePath($path, $classPrefix = 'Zend_View') {

	}

	public function __isset($key) {
		return false;
	}

	public function __unset($key) {
		$this->_smarty->clear_assign($key);
	}

	public function assign($spec, $value = null) {
		$this->_smarty->assign($spec, $value);
	}

	public function clearVars() {
		$this->_smarty->clear_all_assign();
	}

	/**
	 * Функция удобного формирования URL в шаблоне.
	 *
	 * @param array $params
	 */
	public function getUrl(Array $params) {

		if (isset($params['add'])) {
			unset($params['add']);
			$params = array_merge($this->_requestParams, $params);
		}

		//Устанавливаем контроллер
		if (!isset($params['controller'])) {
			$controller = 'index';
		} else {
			$controller = $params['controller'];
			unset($params['controller']);
		}

		//Устанавливаем действие
		if (!isset($params['action'])) {
			$action = 'index';
		} else {
			$action = $params['action'];
			unset($params['action']);
		}

		if (!isset($params['language'])) {
			$language = $this->_requestParams["language"];
		} else {
			$language = $params['language'];
			unset($params['language']);
		}

		//Очищаем пустые параметры
		foreach ($params as $key => $el) {
			if (empty($el) && $el != "0") {
				unset($params[$key]);
			}
		}

		//Удаляем явно указанные параметры
		if (isset($params['del'])) {
			$del_params_array = explode(",", $params['del']);
			unset($params['del']);
			if (is_array($del_params_array) && sizeof($del_params_array) > 0) {
				foreach ($del_params_array as $el) {
					unset($params[trim($el)]);
				}
			}
		}

		$url = PATH_BASE . $language . "/" . $controller . "/" . $action . "/";

		foreach ($params as $key => $el) {
			$url .= $key . "/" . $el . "/";
		}

		return $url;
	}

	/**
	 * Подключаем вспомогательные классы в шаблонизатор
	 * Тут только общие классы
	 *
	 */
	protected function _attachHelpers() {
		//Различные функции
		Zend_Loader::loadClass("ViewHelpers_Mixed", PATH_VIEWS);
		$mixedObj = new ViewHelpers_Mixed();
		$this->_smarty->assign_by_ref("HMixed", $mixedObj);

		//Различные функции работы с контентом
		Zend_Loader::loadClass("ViewHelpers_Cms", PATH_VIEWS);
		$cmsObj = new ViewHelpers_Cms();
		$this->_smarty->assign_by_ref("HCms", $cmsObj);

	}

	public function setTemplate($name) {
		$this->_template = $name;
	}

}