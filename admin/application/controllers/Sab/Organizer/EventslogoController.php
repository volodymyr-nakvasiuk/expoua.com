<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_EventslogoController extends Sab_Organizer_ControllerAbstract {

	protected function _checkAuth() {
		parent::_checkAuth();
		$this->_model->checkUserEventPermission($this->getRequest()->getUserParam("id", 0));
	}

	public function addAction() {}
	public function insertAction() {}
	public function deleteAction() {}
	public function listAction() {}

}