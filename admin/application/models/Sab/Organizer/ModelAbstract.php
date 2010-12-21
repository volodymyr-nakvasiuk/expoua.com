<?PHP

Zend_Loader::loadClass("Sab_ListModelAbstract", PATH_MODELS);

abstract class Sab_Organizer_ModelAbstract extends Sab_ListModelAbstract {

	/**
	 * Экземпляр объекта сессии пользователя
	 *
	 * @var Zend_Session_Namespace
	 */
	public $_user_session = null;

	public function init() {
		//Сперва вызываем функцию инициализации родителя
		parent::init();

		//Устанавливаем пространство имен аутентификации админки
		Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('Auth_Organizer_NS'));

		$this->_user_session = new Zend_Session_Namespace("Shelby_auth_organizer", true);

		//Проверяем, зарегистрирован ли пользватель
		if (!Zend_Auth::getInstance()->hasIdentity()) {
			return false;
		}

		//Устанавливаем глобальные ограничивающие параметры

		$limits = array(
			'organizers_id' => $this->_user_session->operator->organizers_id,
			'users_operators_id' => $this->_user_session->operator->id
		);

		if (!empty($this->_user_session->operator->list_brands)) {
			$limits['brands_id'] = $this->_user_session->operator->list_brands;
		}

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

		$resources = $this->getAllowedResourcesList();
		if ($resources !== false) {
			if (!isset($resources[$controller])) {
				//echo '-';
				return false;
			}
		}

		$user_param_id = Zend_Controller_Front::getInstance()->getRequest()->getUserParam("id", null);
		//Пишем в лог действий пользователя
		$this->_DP("List_LogActions")->insertEntry(array('operator_identity' => $identity, 'controller' => $controller, 'action' => $action, 'param_id' => $user_param_id));

		return $identity;
	}

	public function authCookie($user, $passwd) {
		require_once("Zend/Auth/Adapter/DbTable.php");

		$authAdapterObj = new Zend_Auth_Adapter_DbTable(
			DataproviderAbstract::getDatabaseObjectInstance(),
			"ExpoPromoter_Opt.users_operators",
			"login",
			new Zend_Db_Expr("MD5(passwd)"),
			"?"
		);

		return $this->_authGeneral($authAdapterObj, $user, $passwd);
	}

	/**
	 * Общий интерфейс авторизации
	 * Принимает экземпляр класса адаптера для различных методов авторизации
	 * @param $authAdapterObj
	 * @param $user
	 * @param $passwd
	 * @return unknown_type
	 */
	protected function _authGeneral(Zend_Auth_Adapter_Interface $authAdapterObj, $user, $passwd) {
		$authAdapterObj->setIdentity($user);
		$authAdapterObj->setCredential($passwd);

		$result = Zend_Auth::getInstance()->authenticate($authAdapterObj);

		$row = $authAdapterObj->getResultRowObject();

		if ($result->getCode() === 1 && $row->active == 1 && $row->type == 'organizer') {
			$this->_user_session->operator = $row;

			$organizer_data = $this->_DP("List_Joined_Ep_Organizers")->
				getEntry($row->organizers_id, array('languages_id' => self::$_user_language_id));

			$this->_user_session->organizer = $organizer_data;

			if ($row->super==0) {
				$this->_user_session->operator->list_brands = $this->_DP("List_Operators")->getOrganizerBrands($row->id);

				$resources_ids = $this->_DP("List_Operators")->getOrganizerResources($row->id);
				$resources_list = $this->_DP("List_AclResources")->getList(null, null, array('id' => $resources_ids));

			} else {
				//Массив id модулей, которые доступны организатору.
				//При добавлении нового модуля, его id должен быть добавлен в этот список
				$resources_ids = array(63, 64, 65, 66, 67, 68, 70, 73, 74, 89, 101, 102,
					106, 112, 120, 121, 122, 123, 125, 126, 127, 130, 131);
				$resources_list = $this->_DP("List_AclResources")->getList(null, null, array('id' => $resources_ids));
			}

			foreach ($resources_list['data'] as $el) {
				$this->_user_session->operator->list_resources[$el['code']] = array('delete' => true, 'update' => 'true');
			}

		} else {
			Zend_Auth::getInstance()->clearIdentity();
			return Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
		}

		return $result->getCode();
	}

	public function getUserOrganizerId() {
		return $this->_user_session->operator->organizers_id;
	}

	public function getAllowedResourcesList() {
		if (isset($this->_user_session->operator->list_resources)) {
			return $this->_user_session->operator->list_resources;
		} else {
			return false;
		}
	}

	public function checkUserEventPermission($id) {
		$limit_params = $this->_DP_limit_params;
		$limit_params['id'] = $id;

		$list = $this->_DP("List_Joined_Ep_BrandPlusEvent")->getList(null, null, $limit_params);

		//Если данное событие запрещено к редактированию, умираем.
		if (empty($list['data'])) {
			die("You don't have permissions to access this entry. Your action was logged!");
		}

		return true;
	}

	/**
	 * Возвращает список доступных языков системы
	 *
	 */
	/*public function getLanguagesList() {
	 $list = $this->_DP("List_Languages")->getList(null, null, array('id' => array(1, 2)));
	 return $list['data'];
	 }*/

}