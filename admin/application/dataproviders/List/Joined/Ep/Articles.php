<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_Articles extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('articles', 'articles_data');

	protected $_db_tables_join_by = array(array('articles.id', 'id'));

	protected $_allowed_cols_array = array(array('id', 'date_public', 'create_languages_id'), array('id', 'languages_id', 'active', 'name', 'preambula', 'content'));

	protected $_select_list_cols_array = array(array('id', 'date_created'), array('active', 'name'));

	protected $_sort_col = array('id' => 'DESC');

}