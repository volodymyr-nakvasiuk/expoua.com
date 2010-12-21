<?PHP
Zend_Loader::loadClass("Sab_Company_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Company_EventspartController extends Sab_Company_ControllerAbstract {

	//Переопределяем действия для исключения их использования
	public function listAction() {}
	public function addAction() {}
	public function insertAction() {}
	public function deleteAction() {}

}