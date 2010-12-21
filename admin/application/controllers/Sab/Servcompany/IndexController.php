<?PHP
Zend_Loader::loadClass("Sab_Servcompany_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Servcompany_IndexController extends Sab_Servcompany_ControllerAbstract {

	public function indexAction() {
	}

	//Переопределяем действия для исключения их использования
	/*public function listAction() {}
	public function editAction() {}
	public function addAction() {}
	public function updateAction() {}
	public function insertAction() {}
	public function deleteAction() {}
	*/
}