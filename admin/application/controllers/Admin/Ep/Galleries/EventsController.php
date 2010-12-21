<?PHP
require_once(PATH_CONTROLLERS . "/Admin/ListControllerAbstract.php");

class Admin_Ep_Galleries_EventsController extends Admin_ListControllerAbstract {

	public function listAction() {
		if (!empty($this->_user_param_parent)) {
			$this->_view->entry_event = $this->_model->getEventEntry($this->_user_param_parent);
		}

		parent::listAction();
	}

	public function insertAction() {
		if (empty($this->_user_param_parent)) {
			$this->_setLastActionResult(-3);
			return;
		}

		parent::insertAction();
	}

}