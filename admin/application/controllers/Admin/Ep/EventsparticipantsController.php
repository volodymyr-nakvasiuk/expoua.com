<?PHP

Zend_Loader::loadClass("Admin_ListControllerAbstract", PATH_CONTROLLERS);

class Admin_Ep_EventsparticipantsController extends Admin_ListControllerAbstract {

	public function addAction() {
		$this->_view->list_categories = $this->_model->getCategoriesList();

		$this->_view->list_cities = $this->_model->getCitiesList();
	}

	public function editAction() {
		parent::editAction();

		$this->_view->list_categories = $this->_model->getCategoriesList();

		$this->_view->list_cities = $this->_model->getCitiesList();

	}

}