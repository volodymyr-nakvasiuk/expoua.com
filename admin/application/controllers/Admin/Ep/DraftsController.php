<?PHP

Zend_Loader::loadClass("Admin_ListControllerAbstract", PATH_CONTROLLERS);

class Admin_Ep_DraftsController extends Admin_ListControllerAbstract {

	public function editAction() {
		parent::editAction();

		$this->_view->entry_event = $this->_model->getEventEntry($this->_view->entry['ru']['events_id']);

		$this->_view->list_periods = $this->_model->getPeriodsList();
	}

	public function updateAction() {
		$data = $this->getRequest()->getPost();

		if (empty($data)) {
			$this->_setLastActionResult(-3);
			return;
		}

		if (is_numeric($this->_user_param_id)) {
			$type = $this->getRequest()->getUserParam("type", "");

			if ($type == "update_event") {
				$res = $this->_model->updateEventEntry($this->_user_param_id, $data);
			} else {
				$res = $this->_model->updateEntry($this->_user_param_id, $data);
			}
		} else {
			$res = -2;
		}

		$this->_setLastActionResult($res);
	}

}