<?PHP

Zend_Loader::loadClass("Admin_ListControllerAbstract", PATH_CONTROLLERS);

class Admin_Ep_BrandsController extends Admin_ListControllerAbstract {

	public function editAction() {
		parent::editAction();

		$this->_view->list_categories = $this->_model->getCategoriesList();
	}

	public function addAction() {
		parent::addAction();

		$this->_view->list_categories = $this->_model->getCategoriesList();
	}

}