<?PHP

Zend_Loader::loadClass("Admin_ListControllerAbstract", PATH_CONTROLLERS);

class Admin_Ep_OrganizersController extends Admin_ListControllerAbstract {

	public function editAction() {
		parent::editAction();

		$this->_view->list_socorgs = $this->_model->getSocialOrgsList();
	}

	public function addAction() {
		parent::addAction();

		$this->_view->list_socorgs = $this->_model->getSocialOrgsList();
	}

}