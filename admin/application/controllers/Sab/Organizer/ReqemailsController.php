<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_ReqemailsController extends Sab_Organizer_ControllerAbstract {

	public function listAction() {
		$this->_model->forceListResults = 999;
		parent::listAction();
	}

	//Переопределяем действия для исключения их использования
	public function addAction() {}
	public function editAction() {}
	public function deleteAction() {}
	public function insertAction() {}
}