<?PHP
Zend_Loader::loadClass("Admin_ListControllerAbstract", PATH_CONTROLLERS);

class Admin_System_LogcoordinatorsController extends Admin_ListControllerAbstract {

	public function listAction() {
		parent::listAction();
		
		$this->_view->list_resources = $this->_model->getResourcesList();
		$this->_view->list_users = $this->_model->getUsersList();
		
		$tmp = $this->_prepareSearch();
		$search = array();
		if (is_array($tmp)) {
			foreach ($tmp as $el) {
				$search[$el['column']] = $el['value'];
			}
		}
		$this->_view->search_params = $search;
	}
	
}