<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_ServiceCompCategories extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('service_companies_categories', 'service_companies_categories_data');

	protected $_db_tables_join_by = array(array('service_companies_categories.id', 'id'));

	protected $_allowed_cols_array = array(array('id', 'active'), array('id', 'languages_id', 'name'));

	protected $_select_list_cols_array = array(array('id', 'active'), array('name'));

	protected $_sort_col = array('id' => 'DESC');

}