<?PHP
Zend_Loader::loadClass("Zend_Controller_Action");

abstract class ControllerAbstract extends Zend_Controller_Action {

	/**
	 * Экземпляр класса вида
	 *
	 * @var ViewAbstract
	 */
	protected $_view;

	/**
	 * Экземпляр объекта модели
	 *
	 * @var ModelAbstract
	 */
	protected $_model;

	/**
	 * Текущая просматриваемая страница
	 *
	 * @var int
	 */
	protected $_user_page = 1;

	/**
	 * Пользовательский параметр id из URL
	 * Поскольку используется практически во всех действиях со списками, имеет смысл вынести его в абстрактный класс
	 *
	 * @var int
	 */
	protected $_user_param_id = null;

	/**
	 * Пользовательский параметр parent из URL
	 *
	 * @var int
	 */
	protected $_user_param_parent = null;

	/**
	 * Пользовательский параметр sort, указывающий колонку и направление сортировки
	 *
	 * @var array|null
	 */
	protected $_user_param_sort = array();


	/**
	 * Производим общую инициализацию
	 *
	 */
	public function init() {
		//Устанавливаем обязательное завершение скрипта после редиректа
		$this->_helper->redirector->setExit(true);

		//Создаем экземпляр модели для текущего контроллера
		$this->_initModel();

		//Инициализируем и создаем экземпляр объекта вида
		$this->_initView();

		//Инициализируем языки
		$this->_initLanguages();

		//Вызываем пользовательский инициализатор модели модель
		$this->_model->init();

		//Проверяем сессию пользователя
		$this->_checkAuth();

		//Инициализируем общий параметр page
		$this->_user_page = $this->getRequest()->getUserParam("page", 1);

		//Инициализируем общий параметр id
		$this->_user_param_id = $this->getRequest()->getUserParam("id", null);

		//Инициализируем параметр parent
		$this->_user_param_parent = $this->getRequest()->getUserParam("parent", null);

		$sort = $this->getRequest()->getUserParam("sort", null);
		if (!is_null($sort)) {
			$sort = explode(":", $sort);
			if (sizeof($sort) == 2 && in_array($sort[1], array('DESC', 'ASC'))) {
				$this->_user_param_sort = array($sort[0] => $sort[1]);
			}
		}

	}


	/**
	 * Метод проверки аутентификации. Если она нужна, должен быть переопределен.
	 *
	 * @return boolean
	 */
	protected function _checkAuth() {
		return true;
	}

	/**
	 * Инициализация модели
	 *
	 */
	protected function _initModel() {
		$modelName = $this->getRequest()->getControllerName();
		if (strpos($modelName, "_") === false) {
			$modelName = ucfirst($modelName);
		} else {
			$modelNameArray = explode("_", $modelName);
			$modelName = "";
			foreach ($modelNameArray as $el) {
				$modelName .= ucfirst($el) . "_";
			}
			$modelName = rtrim($modelName, "_");
		}

		$modelName .= "Model";

		try {
			Zend_Loader::loadClass($modelName, PATH_MODELS);
			$this->_model = new $modelName();
		} catch (Exception $e) {
			//echo PATH_MODELS . " " . $modelName . "\n";
			echo "<pre>Error loading model class: " . $e->getMessage();
			echo $e->getTraceAsString();
			echo "\n\n";
			print_r($e->includeErrors);
			echo "</pre>";
			die();
		}

		//Сохраняем экземпляр объекта модели в реестре
		Zend_Registry::set("Shelby_ModelObj", $this->_model);
	}

	/**
	 * Инициализация объекта вида для пользовательской части
	 * Объект вида админ части немного отличается, для этого производится перегрузка метода _initView() в абстрактном классе админки
	 *
	 */
	protected function _initView() {

		Zend_Loader::loadClass("Frontend_View", PATH_VIEWS);
		$this->_view = new Frontend_View();
	}

	protected function _initLanguages() {
		//Получаем выбранный язык
		$user_language = $this->getRequest()->getParam("language");

		$language = null;
		$language_id = 0;

		$list_languages = $this->_model->getLanguagesList();
		foreach ($list_languages as $el) {
			if ($el['code'] == $user_language) {
				$language = $el['code'];
				$language_id = $el['id'];
				break;
			}
		}

		if (is_null($language)) {
			$language = LANGUAGE_DEFAULT;

			foreach ($list_languages as $el) {
				if ($el['code'] == $language) {
					$language_id = $el['id'];
					break;
				}
			}
		}

		//Передаем id текущего языка в модель
		$this->_model->setCurrentLanguage($language_id);

		//Передаем список языков в шаблонизатор
		$this->_view->list_languages = $list_languages;

		//Передаем язык в шаблонизатор
		$this->_view->selected_language = $language;

		//Сохраняем id языка в реестре
		Zend_Registry::set("language_id", $language_id);
		Zend_Registry::set("language_code", $language);
	}


	/**
	 * Устанавливает результат выполнения последнего действия и передает его в шаблонизатор
	 * Функция должна вызываться из каждого действия, где это необходимо
	 *
	 * @param mixed $result
	 */
	protected function _setLastActionResult($result) {
		$this->_view->last_action_result = $result;
	}

	/**
	 * Возвращает результат выполнения последнего действия
	 * Функция должна вызываться из каждого действия, где это необходимо
	 *
	 * @param mixed $result
	 */
	protected function _lastActionResult() {
		return $this->_view->last_action_result;
	}

	/**
	 * Подготовка поискового параметра
	 * Определяем ее в базовом классе, поскольку используется практически везде
	 *
	 * @return array|null
	 */
	protected function _prepareSearch() {
		$result = array();

		$search_param = $this->getRequest()->getUserParam("search", null);

		if (!is_null($search_param)) {

			$search_elements_array = explode(";", $search_param);

			foreach ($search_elements_array as $search_element) {

				if (strpos($search_element, ">=") !== false) {
					$tmp = explode(">=", $search_element, 2);
					$type = ">=";
				} elseif (strpos($search_element, "<=") !== false) {
					$tmp = explode("<=", $search_element, 2);
					$type = "<=";
				} elseif (strpos($search_element, ":") !== false) {
					$tmp = explode(":", $search_element, 2);
					$type = "=";
				} elseif (strpos($search_element, "=") !== false) {
					$tmp = explode("=", $search_element, 2);
					$type = "=";
				} elseif (strpos($search_element, ">") !== false) {
					$tmp = explode(">", $search_element, 2);
					$type = ">";
				} elseif (strpos($search_element, "<") !== false) {
					$tmp = explode("<", $search_element, 2);
					$type = "<";
				} elseif (strpos($search_element, "~") !== false) {
					$tmp = explode("~", $search_element, 2);
					$type = "~";
				} else {
					//Непонятно что за условие, пропускаем
					continue;
				}

				//Если поисковая строка задана неверно, сбрасываем ее
				if (sizeof($tmp)==2) {
					$result[] = array('column' => $tmp[0], 'value' => $tmp[1], 'type' => $type);
				}
			}
		}

		//Zend_Debug::dump($result);

		return (empty($result) ? null:$result);
	}

	/**
	 * Деструктор класса. Выводим данные в браузер пользователя
	 * При необходимости, производим перенаправление на адрес, указанный в параметре redirURL
	 *
	 */
	public function __destruct() {

		//Проверяем, установлен ли параметр принудительного перенаправления
		$redirectUrl = $this->getRequest()->getParam("redirURL", null);
		if (!is_null($redirectUrl)) {
			//Производим перенаправление на переданный адрес
			$this->_helper->redirector->setPrependBase(false);
			$this->_helper->redirector->gotoUrl($redirectUrl);
		}

		$this->_view->render("");
	}
}