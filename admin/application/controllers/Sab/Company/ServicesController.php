<?PHP
Zend_Loader::loadClass("Sab_Company_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Company_ServicesController extends Sab_Company_ControllerAbstract {

	public function addAction() {
		parent::addAction();
		$this->_view->list_categories = $this->_model->getCategoriesList();
		//$this->_view->tree_categories = $this->_model->getExhibitionCategoriesTree();
	}

	public function editAction() {
		parent::editAction();
		$this->_view->list_categories = $this->_model->getCategoriesList();

		//echo $this->_view->entry['brands_subcategories_id'];
		$entry = $this->_view->entry;
		$entry = array_pop($entry);
		if (!empty($entry['brands_subcategories_id'])) {
			$entry_subcat = $this->_model->getSubCategoryEntry($entry['brands_subcategories_id']);
			$this->_view->list_subcategories = $this->_model->getSubCategoriesList($entry_subcat['parent_id']);

			$entry = $this->_view->entry;
			foreach ($entry as &$el) {
				$el['brands_categories_id'] = $entry_subcat['parent_id'];
			}
			$this->_view->entry = $entry;
		}
	}

}