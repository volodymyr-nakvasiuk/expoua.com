<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_VotesElements extends List_Abstract {

	protected $_allowed_cols = array('id', 'languages_id', 'active', 'parent_id', 'sort_order', 'name', 'votes_num');

	protected $_db_table = "cms_votes_elements";

	protected $_select_list_cols = array('id', 'parent_id', 'active', 'name', 'sort_order', 'votes_num');

	protected $_sort_col = array('sort_order' => 'DESC');
}