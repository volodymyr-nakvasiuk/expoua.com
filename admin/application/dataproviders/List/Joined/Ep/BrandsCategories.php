<?PHP
Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_BrandsCategories extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('brands_categories', 'brands_categories_data');

	protected $_db_tables_join_by = array(array('brands_categories.id', 'id'));

	protected $_allowed_cols_array = array(
		array('active', 'global_sync_id'),
		array('id', 'languages_id', 'name')
	);

	protected $_select_list_cols_array = array(
		array('id', 'active'),
		array('name')
	);

	protected $_sort_col = array('id' => 'DESC');

}