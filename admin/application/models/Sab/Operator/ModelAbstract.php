<?PHP

Zend_Loader::loadClass("Sab_ListModelAbstract", PATH_MODELS);

abstract class Sab_Operator_ModelAbstract extends Sab_ListModelAbstract {

	/**
	 * Экземпляр объекта сессии пользователя
	 *
	 * @var Zend_Session_Namespace
	 */
	protected $_user_session = null;

	public function init() {
		//Сперва вызываем функцию инициализации родителя
		parent::init();

		//Устанавливаем пространство имен аутентификации админки
		Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('Auth_Operator_NS'));

		$this->_user_session = new Zend_Session_Namespace("Shelby_auth_operator", true);

		//Проверяем, зарегистрирован ли пользватель
		if (!Zend_Auth::getInstance()->hasIdentity()) {
			return false;
		}

		$identity = Zend_Auth::getInstance()->getIdentity();
		$countries = $this->_DP("List_Operators")->getOperatorCountriesByName($identity);

		$this->_DP_limit_params = array_merge($this->_DP_limit_params,
			array(
				'users_operators_id' => $this->_user_session->operator->id,
				'countries_id' => $countries,
				'type' => 'add',
				'brand_dead' => 0,
				'_only_wfe' => true
			));
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

}