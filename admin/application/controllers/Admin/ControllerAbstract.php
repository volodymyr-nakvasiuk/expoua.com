<?PHP

abstract class Admin_ControllerAbstract extends ControllerAbstract {

	/**
	 * Перегружаем общую функцию инициализации
	 *
	 */
  public function init() {
    parent::init();
    
    $this->_getMessagesLists();
  }


	/**
	 * Перегружаем функцию инициализации вида
	 *
	 */
	protected function _initView() {

		$responseType = $this->getRequest()->getUserParam("feed", null);

		switch ($responseType) {
			case "xml":
				Zend_Loader::loadClass("Admin_Feed_Xml", PATH_VIEWS);
				$this->_view = new Admin_Feed_Xml();
				break;
			case "json":
				Zend_Loader::loadClass("Admin_Feed_Json", PATH_VIEWS);
				$this->_view = new Admin_Feed_Json();
				break;
			case "null":
				Zend_Loader::loadClass("Admin_Feed_Null", PATH_VIEWS);
				$this->_view = new Admin_Feed_Null();
				break;
			case "html":
			default:
				Zend_Loader::loadClass("Admin_View", PATH_VIEWS);
				$this->_view = new Admin_View();
		}

	}

	protected function _checkAuth() {
		$userSession = $this->_model->checkUserSession();

		//Если пользователь не зарегистрирован и не находится в модуле аутентификации, направляем его туда
		if ($userSession === false && $this->getRequest()->getControllerName() != 'admin_auth') {
			try {
				$this->_helper->redirector->goto('index', 'admin_auth', LANGUAGE_DEFAULT);
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}

		$this->_view->session_user = $userSession;
		$this->_view->session_user_allow = $this->_model->getAllowedResourcesList();

	}


	/**
	 * Узнать список вопросов / ответов
	 *
	 */
	protected function _getMessagesLists() {
		$this->_view->oq_list = $this->_model->getOperatorsMsgList();
		$this->_view->aq_list = $this->_model->getAdvertisersMsgList();
	}


}