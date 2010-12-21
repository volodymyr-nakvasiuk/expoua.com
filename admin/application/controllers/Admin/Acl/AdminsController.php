<?PHP
Zend_Loader::loadClass("Admin_ListControllerAbstract", PATH_CONTROLLERS);

class Admin_Acl_AdminsController extends Admin_ListControllerAbstract {

	public function listAction() {
		parent::listAction();

		$this->_view->list_groups = $this->_model->getGroupsList();
	}

	public function editAction() {
		parent::editAction();

		$this->_view->list_groups = $this->_model->getGroupsList();
	}

}