<?PHP

Zend_Loader::loadClass("Admin_ControllerAbstract", PATH_CONTROLLERS);

class Admin_Ep_Banners_StatController extends Admin_ControllerAbstract {

	public function listAction() {
		$search = $this->_prepareSearch();

		$this->_view->list = $this->_model->getList($this->_user_page, $this->_user_param_parent, $this->_user_param_sort, $search);
	}

	public function viewAction() {
		$this->_view->entry = $this->_model->getEntry($this->_user_param_id);
	}

}