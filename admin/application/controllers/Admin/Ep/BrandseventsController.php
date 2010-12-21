<?PHP

Zend_Loader::loadClass("Admin_ListControllerAbstract", PATH_CONTROLLERS);

class Admin_Ep_BrandseventsController extends Admin_ListControllerAbstract {

	public function addAction() {
		parent::addAction();
		
		$this->_view->list_periods = $this->_model->getPeriodsList();
		$this->_view->list_brand_categories = $this->_model->getCategoriesList();
	}
	
}