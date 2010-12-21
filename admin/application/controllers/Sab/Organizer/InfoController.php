<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_InfoController extends Sab_Organizer_ControllerAbstract {

	protected function _initView() {
		Zend_Loader::loadClass("Admin_View", PATH_VIEWS);
		$this->_view = new Admin_View();

		$this->_view->setTemplate("coreOrganizerInfo.tpl");
	}
	
	protected function _checkAuth() {
		$this->_view->session_user = $this->_model->checkUserSession();
	}
	
	public function __call($name, $arguments) {
		$this->indexAction();
	}
	
	public function indexAction() {
		$this->_helper->redirector->gotoUrl(
			$this->_view->getUrl(array('controller' => 'sab_organizer_auth', 'action' => 'login')));
	}
	
	public function viewAction() {
		if ($this->_model->checkUserSession() !== false) {
			parent::_checkAuth();
			$this->_view->setTemplate("coreOrganizer.tpl");
			
			$this->_view->entry = $this->_model->getCmsPage($this->_user_param_id);
		}
	}

	public function editAction() {
		$this->indexAction();
	}
	public function updateAction() {
		$this->indexAction();
	}
	public function listAction() {
		$this->indexAction();
	}
	public function insertAction() {
		$this->indexAction();
	}
	public function whatisAction() {}
	public function websitesAction() {}
	public function statsAction() {}
	public function termsAction() {}
}