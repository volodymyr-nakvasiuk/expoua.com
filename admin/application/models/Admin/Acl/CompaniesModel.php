<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Acl_CompaniesModel extends Admin_ListModelAbstract {

	protected $_DP_name = "List_Users_Companies";

}