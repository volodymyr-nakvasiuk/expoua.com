<?PHP
require_once(PATH_CONTROLLERS. "/Admin/ListControllerAbstract.php");

class Admin_Ep_Banners_PublishersController extends Admin_ListControllerAbstract {

	public function addAction() {
		parent::addAction();

		$this->_view->list_places = $this->_model->getBannerPlacesList();
	}

	public function editAction() {
		parent::editAction();

		$this->_view->list_places = $this->_model->getBannerPlacesList();
	}

}