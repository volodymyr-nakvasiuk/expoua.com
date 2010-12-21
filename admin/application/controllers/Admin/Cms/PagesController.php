<?PHP
Zend_Loader::loadClass("Admin_ListControllerAbstract", PATH_CONTROLLERS);

class Admin_Cms_PagesController extends Admin_ListControllerAbstract {

	public function addAction() {
		$this->_view->trail = $this->_model->getTrail($this->_user_param_parent);
		$this->_view->list_templates = $this->_model->getTemplatesList();
	}

	public function editAction() {
		parent::editAction();

		$this->_view->trail = $this->_model->getTrail($this->_user_param_parent);
		$this->_view->list_templates = $this->_model->getTemplatesList();
	}

	public function listAction() {
		parent::listAction();

		$this->_view->trail = $this->_model->getTrail($this->_user_param_parent);
	}

	public function treeAction() {
		$this->_view->tree = $this->_model->getTree($this->_user_param_parent);
		$this->_view->trail = $this->_model->getTrail($this->_user_param_parent);
	}

}