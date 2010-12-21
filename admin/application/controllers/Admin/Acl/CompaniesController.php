<?PHP
Zend_Loader::loadClass("Admin_ListControllerAbstract", PATH_CONTROLLERS);

class Admin_Acl_CompaniesController extends Admin_ListControllerAbstract {

	public function editAction() {
		$entry = $this->_model->getEntry($this->_user_param_id);
		
		$entry['functions'] = explode(',', $entry['functions']);
		
		$this->_view->entry = $entry;
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

		if (is_numeric($this->_user_param_id)) {
  		$data['functions'] = implode(',', $data['functions']);

			$res = $this->_model->updateEntry($this->_user_param_id, $data);
		} else {
			$res = -2;
		}

		$this->_setLastActionResult($res);
	}


}