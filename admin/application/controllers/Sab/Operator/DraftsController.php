<?PHP
Zend_Loader::loadClass("Sab_Operator_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Operator_DraftsController extends Sab_Operator_ControllerAbstract {

	public function addAction() {
		$this->_view->entry_event = $this->_model->getEventEntry($this->_user_param_id);
		$this->_view->list_periods = $this->_model->getPeriodsList();

		if (empty($this->_user_param_id)) {
			$this->_view->list_brand_categories = $this->_model->getBrandCategoriesList();
		}
	}

	public function editAction() {
		parent::editAction();

		$this->_view->list_periods = $this->_model->getPeriodsList();
		$this->_view->list_brand_categories = $this->_model->getBrandCategoriesList();
	}

}