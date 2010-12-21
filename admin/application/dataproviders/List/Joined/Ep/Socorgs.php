<?PHP
Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_Socorgs extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('social_organizations', 'social_organizations_data');

	protected $_db_tables_join_by = array(array('social_organizations.id', 'id'));

	protected $_allowed_cols_array = array(
		array('id', 'active', 'cities_id', 'global_sync_id'),
		array('id', 'languages_id', 'name', 'address', 'postcode', 'phone', 'fax', 'email',
			'web_address', 'description', 'logo')
	);

	protected $_select_list_cols_array = array(array('id', 'active'), array('name'));

	protected $_sort_col = array('id' => 'DESC');

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->join("location_cities_data",
			"social_organizations.cities_id = location_cities_data.id", array("city_name" => 'name'));
		$select->where("location_cities_data.languages_id = social_organizations_data.languages_id");
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->join("location_cities_data",
			"social_organizations.cities_id = location_cities_data.id", array("city_name" => 'name'));
		$select->where("location_cities_data.languages_id = social_organizations_data.languages_id");
	}
}