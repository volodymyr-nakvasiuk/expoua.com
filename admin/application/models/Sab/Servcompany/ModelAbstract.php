<?PHP

Zend_Loader::loadClass("Sab_ListModelAbstract", PATH_MODELS);

abstract class Sab_Servcompany_ModelAbstract extends Sab_ListModelAbstract {

	/**
	 * Экземпляр объекта сессии пользователя
	 *
	 * @var Zend_Session_Namespace
	 */
	protected $_user_session = null;

	protected $servcomp_id = null;


	public function init() {
		//Сперва вызываем функцию инициализации родителя
		parent::init();

		//Устанавливаем пространство имен аутентификации админки
		Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('Auth_Servcompany_NS'));

		$this->_user_session = new Zend_Session_Namespace("Shelby_auth_servcompany", true);

		//echo "<!-- ";
		//print_r($this->_user_session->operator);
		//echo " -->";

		//Проверяем, зарегистрирован ли пользватель
		if (!Zend_Auth::getInstance()->hasIdentity()) {
			return false;
		}

    // Сохраняем ID сервисника
    $this->servcomp_id = $this->getUserServcompanyId();

		//Устанавливаем глобальные ограничивающие параметры

		$limits = array(
			'service_companies_id' => $this->servcomp_id,
			'servcomps_id' => $this->servcomp_id,
			'users_operators_id' => $this->_user_session->servcompany->id
		);
		
    $this->_DP_limit_params = array_merge($this->_DP_limit_params, $limits);
		
	}

	/**
	 * Проверяем сессию пользователя.
	 * Если пользователь зарегистрирован функция возвращает его логин, иначе false;
	 *
	 * @return boolean|string
	 */
	public function checkUserSession() {

		$identity = Zend_Auth::getInstance()->hasIdentity();

		//Сперва проверяем зарегистрирован ли пользователь
		if ($identity === true) {
			$identity = Zend_Auth::getInstance()->getIdentity();
		} else {
			return false;
		}

		//Проверяем имеет ли он соответствующие привелегии для доступа к текущему модулю и действию
		$controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
		$action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

		$user_param_id = Zend_Controller_Front::getInstance()->getRequest()->getUserParam("id", null);
		//Пишем в лог действий пользователя
		$this->_DP("List_LogActions")->insertEntry(array('operator_identity' => $identity, 'controller' => $controller, 'action' => $action, 'param_id' => $user_param_id));

		return $identity;
	}

	public function getUserServcompanyId() {
		return $this->_user_session->servcompany->service_companies_id;
	}

}

