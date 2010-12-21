<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Acl_ServcompanyModel extends Admin_ListModelAbstract {

	protected $_DP_name = "List_Operators";

	protected $_DP_limit_params = array('type' => 'servcompany');

}