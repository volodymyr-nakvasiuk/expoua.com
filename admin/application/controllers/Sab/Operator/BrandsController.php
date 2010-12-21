<?PHP
Zend_Loader::loadClass("Sab_Operator_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Operator_BrandsController extends Sab_Operator_ControllerAbstract {

	/*protected function _initView() {
		parent::_initView();
		$this->_view->setTemplate("controllers_frontend/sab_operator_brands/core.tpl");
	}*/
	
	public function listAction() {
		if ($this->getRequest()->getUserParam("template", "") == "simple") {
			$this->_view->setTemplate("controllers_frontend/sab_operator_brands/core.tpl");
		}
		parent::listAction();
	}
	
	//Переопределяем действия для исключения их использования
	public function editAction() {}
	public function addAction() {}
	public function updateAction() {}
	public function insertAction() {}
	public function deleteAction() {}

}