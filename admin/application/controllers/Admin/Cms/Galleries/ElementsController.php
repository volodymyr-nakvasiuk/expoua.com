<?PHP
Zend_Loader::loadClass("Admin_ListControllerAbstract", PATH_CONTROLLERS);

class Admin_Cms_Galleries_ElementsController extends Admin_ListControllerAbstract {

	public function listAction() {
		parent::listAction();

		$this->_view->files_base_path = PATH_WEB_DATA_GALLERIES;
	}

}