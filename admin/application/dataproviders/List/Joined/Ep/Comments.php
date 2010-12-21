<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_Comments extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('comments', 'comments_data');

	protected $_db_tables_join_by = array(array('comments.id', 'id'));

	protected $_allowed_cols_array = array(array('expocenters_id', 'service_companies_id', 'news_id', 'articles_id'), array('id', 'languages_id', 'email', 'name', 'message'));

	protected $_select_list_cols_array = array(array('id', 'expocenters_id', 'service_companies_id', 'news_id', 'articles_id'), array('email', 'name'));

	protected $_sort_col = array('id' => 'DESC');

	protected $_prepare_cols = array(
				'expocenters_id' => array('num', null),
				'service_companies_id' => array('num', null),
				'news_id' => array('num', null),
				'articles_id' => array('num', null)
	);

}