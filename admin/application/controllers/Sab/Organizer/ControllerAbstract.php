<?PHP

Zend_Loader::loadClass("Sab_ListControllerAbstract", PATH_CONTROLLERS);

abstract class Sab_Organizer_ControllerAbstract extends Sab_ListControllerAbstract {

	/**
	 * Перегружаем функцию инициализации вида
	 *
	 */
	protected function _initView() {
		$responseType = $this->getRequest()->getUserParam("feed", null);

		switch ($responseType) {
			case "csv":
				Zend_Loader::loadClass("Admin_Feed_Csv", PATH_VIEWS);
				$this->_view = new Admin_Feed_Csv();
				break;
            case "xml":
                Zend_Loader::loadClass("Admin_Feed_Xml", PATH_VIEWS);
                $this->_view = new Admin_Feed_Xml();
                break;
			case "html":
			default:
				Zend_Loader::loadClass("Admin_View", PATH_VIEWS);
				$this->_view = new Admin_View();
		}

		$this->_view->setTemplate("coreOrganizer.tpl");
	}

	protected function _checkAuth() {
		$userSession = $this->_model->checkUserSession();

		if ($userSession === false) {
			//Автологин
			$login = $this->getRequest()->getCookie("login", null);
			if (!empty($login)) {
				$res = $this->_model->authCookie($login,
					$this->getRequest()->getCookie("passwd", ""));
				if ($res == 1) {
					//Перегружем чтобы ограничениея аккаунта вступили в силу
					$this->_helper->redirector->gotoUrl($this->_view->getUrl(array('add' => 1)));
				}
			}
			
			try {
				$this->_helper->redirector->goto('whatis', 'sab_organizer_info', null,
					array('language' => Zend_Registry::get("language_code")));
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}

		$this->_view->session_user = $userSession;
		$this->_view->organizer = $this->_model->_user_session->organizer;

		$controller = $this->getRequest()->getControllerName();
		//$this->_view->session_user_allow = array($controller => array('delete' => true, 'update' => 'true'));

		$this->_view->session_user_orgid = $this->_model->getUserOrganizerId();
		$this->_view->session_user_allow = $this->_model->getAllowedResourcesList();
	}

}