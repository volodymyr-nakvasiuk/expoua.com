<?PHP
Zend_Loader::loadClass("Sab_Company_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Company_HelpController extends Sab_Company_ControllerAbstract {

   public function indexAction() {
   }

   public function insertAction() {
   	$data = $this->getRequest()->getPost();

   	if (!empty($data)) {
   		$res = $this->_model->sendMessage($data);
   	}

   	$this->_setLastActionResult($res);
   }

	//Переопределяем действия для исключения их использования
	public function listAction() {}
	public function addAction() {}
	public function editAction() {}
	public function deleteAction() {}
}