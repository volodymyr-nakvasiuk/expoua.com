<?PHP
Zend_Loader::loadClass("Admin_Files_ControllerAbstract", PATH_CONTROLLERS);

class Admin_Files_DownloadController extends Admin_Files_ControllerAbstract {

	public function listAction() {
		parent::listAction();

		$this->_view->files_base_path = PATH_WEB_DATA_DOWNLOAD;
	}

}