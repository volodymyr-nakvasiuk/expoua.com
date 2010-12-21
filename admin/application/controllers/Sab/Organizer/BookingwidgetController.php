<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_BookingwidgetController extends Sab_Organizer_ControllerAbstract {

	public function viewAction() {
		$this->_view->list_events = $this->_model->getList();
        $this->_view->entry_user = $this->_model->getUserInfo();
		
		if (!empty($this->_user_param_id)) {
			$entry_event = $this->_model->getEntry($this->_user_param_id);
			$this->_view->entry_event = $entry_event;
			$this->_view->entry_location = $this->_model->getLocation($entry_event['cities_id']);
			$this->_view->entry_affiliate = $this->_model->getAffiliateByEventsId($this->_user_param_id);
		}
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
	public function listAction() {
		$this->indexAction();
	}
	public function insertAction() {
		$this->indexAction();
	}
	public function __call($name, $arguments) {
		$this->indexAction();
	}
	
}