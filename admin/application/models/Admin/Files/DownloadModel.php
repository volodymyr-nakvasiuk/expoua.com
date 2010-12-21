<?PHP

Zend_Loader::loadClass("Admin_Files_ModelAbstract", PATH_MODELS);

class Admin_Files_DownloadModel extends Admin_Files_ModelAbstract {

	protected $_DP_name = "Filesystem_Files";

	protected $_FS_Base_Path = PATH_FRONTEND_DATA_DOWNLOAD;
}