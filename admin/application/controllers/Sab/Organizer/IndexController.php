<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_IndexController extends Sab_Organizer_ControllerAbstract {

	public function indexAction() {
	}

	//Переопределяем действия для исключения их использования
	public function listAction() {}
	public function editAction() {}
	public function addAction() {}
	public function updateAction() {}
	public function insertAction() {}
	public function deleteAction() {}
}