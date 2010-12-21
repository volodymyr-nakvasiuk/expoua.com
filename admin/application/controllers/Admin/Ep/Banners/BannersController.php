<?PHP
require_once(PATH_CONTROLLERS. "/Admin/ListControllerAbstract.php");

class Admin_Ep_Banners_BannersController extends Admin_ListControllerAbstract {

	public function addAction() {
		parent::addAction();

		$this->_view->list_types = $this->_model->getTypesList();
	}

	public function editAction() {
		parent::editAction();

		$this->_view->list_types = $this->_model->getTypesList();
	}

}