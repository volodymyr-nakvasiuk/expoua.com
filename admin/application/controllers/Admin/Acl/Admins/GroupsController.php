<?PHP
Zend_Loader::loadClass("Admin_ListControllerAbstract", PATH_CONTROLLERS);

class Admin_Acl_Admins_GroupsController extends Admin_ListControllerAbstract {

	public function editAction() {
		parent::editAction();

		$this->_view->list_groups = $this->_model->getFullList();
		$this->_view->list_resources = $this->_model->getResourcesList($this->_user_param_id);
	}

}