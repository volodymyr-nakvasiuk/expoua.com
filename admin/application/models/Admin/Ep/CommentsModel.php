<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Ep_CommentsModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_Comments';

}