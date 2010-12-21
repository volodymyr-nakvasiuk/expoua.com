<?PHP

Zend_Loader::loadClass("Sab_ListControllerAbstract", PATH_CONTROLLERS);

abstract class Sab_Servcompany_ControllerAbstract extends Sab_ListControllerAbstract {

	/**
	 * Перегружаем функцию инициализации вида
	 *
	 */
	protected function _initView() {
		Zend_Loader::loadClass("Admin_View", PATH_VIEWS);
		$this->_view = new Admin_View();

		$this->_view->setTemplate("coreServcompany.tpl");
	}

	protected function _checkAuth() {
		$userSession = $this->_model->checkUserSession();

		//Если пользователь не зарегистрирован и не находится в модуле аутентификации, направляем его туда
		if ($userSession === false && $this->getRequest()->getControllerName() != 'sab_servcompany_auth') {
			try {
				$this->_helper->redirector->goto('index', 'sab_servcompany_auth', Zend_Registry::get("language_code"));
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}

		if (!$userSession) {
			return;
		}

		$this->_view->session_user = $userSession;

		$controller = $this->getRequest()->getControllerName();;
		//$this->_view->session_user_allow = array($controller => array('delete' => true, 'update' => 'true'));

		$this->_view->session_user_servid = $this->_model->getUserServcompanyId();
		//$this->_view->session_user_allow = $this->_model->getAllowedResourcesList();
	}

}