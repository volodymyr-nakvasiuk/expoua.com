<?PHP

Zend_Loader::loadClass("Admin_Ep_Companies_ControllerAbstract", PATH_CONTROLLERS);

class Admin_Ep_Companies_ManageController extends Admin_Ep_Companies_ControllerAbstract {

	public function addAction() {
		parent::addAction();

		$this->_view->list_categories = $this->_model->getCategoriesList();
	}

	public function editAction() {
		parent::editAction();

		$this->_view->list_categories = $this->_model->getCategoriesList();
	}

}