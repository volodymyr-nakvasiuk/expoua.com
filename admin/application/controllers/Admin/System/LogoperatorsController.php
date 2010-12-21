<?PHP

Zend_Loader::loadClass("Admin_ListControllerAbstract", PATH_CONTROLLERS);

class Admin_System_LogoperatorsController extends Admin_ListControllerAbstract {

	public function listAction() {
		parent::listAction();

		$this->_view->list_operators = $this->_model->getOperatorsList();
	}

}