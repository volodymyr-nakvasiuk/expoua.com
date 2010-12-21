<?PHP

Zend_Loader::loadClass("Admin_Files_ModelAbstract", PATH_MODELS);

class Admin_Files_FlashModel extends Admin_Files_ModelAbstract {

	protected $_DP_name = "Filesystem_Flash";

	protected $_FS_Base_Path = PATH_FRONTEND_DATA_FLASH;
}