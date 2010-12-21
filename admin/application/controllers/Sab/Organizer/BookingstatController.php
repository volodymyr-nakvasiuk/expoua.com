<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_BookingstatController extends Sab_Organizer_ControllerAbstract {

	public function viewAction() {
		
	}
	
	public function indexAction() {
		$this->_helper->redirector->gotoUrl(
			$this->_view->getUrl(array('add' => 1, 'action' => 'view')));
	}
	public function editAction() {
		$this->indexAction();
	}
	public function updateAction() {
		$this->indexAction();
	}
	public function insertAction() {
		$this->indexAction();
	}
	public function __call($name, $arguments) {
		$this->indexAction();
	}
	
}