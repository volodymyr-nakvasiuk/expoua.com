<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_DraftsController extends Sab_Organizer_ControllerAbstract {

	public function addAction() {
		$this->_view->entry_event = $this->_model->getEventEntry($this->_user_param_id);
		$this->_view->list_periods = $this->_model->getPeriodsList();
		$this->_view->list_brand_categories = $this->_model->getBrandCategoriesList();
	}

	public function editAction() {
		parent::editAction();

		$this->_view->list_periods = $this->_model->getPeriodsList();
		$this->_view->list_brand_categories = $this->_model->getBrandCategoriesList();
	}

	public function previewAction() {
		$this->_view->setTemplate('controllers_frontend/sab_organizer_drafts/preview.tpl');
		$data = $this->getRequest()->getPost();

		$this->_view->data = $data;
	}
}