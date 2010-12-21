<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_AclController extends Sab_Organizer_ControllerAbstract {

	public function editAction() {
		parent::editAction();

		$this->_view->list_brands = $this->_model->getBrandsList();
	}

	public function addAction() {
		parent::addAction();

		$this->_view->list_brands = $this->_model->getBrandsList();
	}

}