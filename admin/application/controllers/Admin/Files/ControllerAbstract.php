<?PHP
Zend_Loader::loadClass("Admin_ListControllerAbstract", PATH_CONTROLLERS);

abstract class Admin_Files_ControllerAbstract extends Admin_ListControllerAbstract {

	public function listAction() {
		parent::listAction();

		$this->_view->trail = $this->_model->getTrail($this->_user_param_parent);
	}

	public function deleteAction() {
		if (is_string($this->_user_param_id)) {
			$res = $this->_model->deleteEntry($this->_user_param_id, $this->_user_param_parent);
		} else {
			$res = -2;
		}

		$this->_setLastActionResult($res);
	}

	public function updateAction() {

		$data = $this->getRequest()->getPost();

		if (empty($data)) {
			$this->_setLastActionResult(-3);
			return;
		}

		if (!isset($data['parent_id'])) {
			$data['parent_id'] = $this->_user_param_parent;
		}

		if (is_string($this->_user_param_id)) {
			$res = $this->_model->updateEntry($this->_user_param_id, $data);
		} else {
			$res = -2;
		}

		$this->_setLastActionResult($res);
	}

}