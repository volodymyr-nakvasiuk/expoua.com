<?PHP
Zend_Loader::loadClass("Sab_Operator_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Operator_BrandseventsController extends Sab_Operator_ControllerAbstract {

	public function listAction() {
		if ($this->getRequest()->getUserParam('show', "") == 'all') {
			$this->_model->setShowAllMode();
		}
		
		parent::listAction();
		
		$this->_view->list_brands_marked = $this->_model->getDistinctDraftsBrandsList();
	}

	//Переопределяем действия для исключения их использования
	public function editAction() {}
	public function addAction() {}
	public function updateAction() {}
	public function insertAction() {}
	public function deleteAction() {}

}