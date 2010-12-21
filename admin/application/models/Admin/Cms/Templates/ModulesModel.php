<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Cms_Templates_ModulesModel extends Admin_ListModelAbstract {

	protected $_DP_name = "List_Templates";

	protected $_DP_limit_params = array('tpl_cat_id' => 5);
}