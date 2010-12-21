<?PHP
require_once(PATH_CONTROLLERS. "/Admin/ListControllerAbstract.php");

class Admin_Ep_Banners_PlansController extends Admin_ListControllerAbstract {

	public function addAction() {
		parent::addAction();

		$this->_getLists();
	}

	public function editAction() {
		parent::editAction();

		$this->_getLists();
		//$this->_view->list_publishers = $this->_model->getPublishersList();
	}

	public function listAction() {
		parent::listAction();

		$this->_view->list_places = $this->_model->getPlacesList();
	}

	private function _getLists() {
		$this->_view->list_modules = $this->_model->getModulesList();
		$this->_view->list_categories = $this->_model->getCategoriesList();
		$this->_view->list_places = $this->_model->getPlacesList();
	}

}