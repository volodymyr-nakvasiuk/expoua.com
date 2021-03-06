<?PHP
Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_Countries extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('location_countries', 'location_countries_data');

	protected $_db_tables_join_by = array(array('location_countries.id', 'id'));

	protected $_allowed_cols_array = array(
		array('id', 'regions_id', 'active', 'code', 'global_sync_id'),
		array('id', 'languages_id', 'name')
	);

	protected $_select_list_cols_array = array(
		array('id', 'active', 'regions_id'),
		array('name')
	);

	protected $_sort_col = array('id' => 'DESC');

}