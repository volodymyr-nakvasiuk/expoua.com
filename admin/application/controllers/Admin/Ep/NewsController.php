<?PHP

Zend_Loader::loadClass("Admin_ListControllerAbstract", PATH_CONTROLLERS);

class Admin_Ep_NewsController extends Admin_ListControllerAbstract {

	public function editAction() {
		parent::editAction();

		$this->_view->list_brand_categories = $this->_model->getBrandCategoriesList();
		$this->_view->list_countries = $this->_model->getCountriesList();
	}

	public function addAction() {
		$this->_view->list_brand_categories = $this->_model->getBrandCategoriesList();
		$this->_view->list_countries = $this->_model->getCountriesList();
	}

}