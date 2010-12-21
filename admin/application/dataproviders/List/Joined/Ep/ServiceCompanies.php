<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_ServiceCompanies extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('service_companies', 'service_companies_data');

	protected $_db_tables_join_by = array(array('service_companies.id', 'id'));

	protected $_allowed_cols_array = array(
		array('id', 'social_organizations_id', 'service_companies_categories_id', 'active', 'cities_id', 'sort_order', 'sort_order_cat', 'email_requests'),
		array('id', 'languages_id', 'name', 'description', 'address', 'postcode', 'phone', 'fax', 'email', 'web_address', 'activity_forms', 'additional_info', 'logo'));

	protected $_select_list_cols_array = array(array('id', 'active'), array('name'));

	protected $_sort_col = array('id' => 'DESC');

	protected $_prepare_cols = array(
				'social_organizations_id' => array('num', null),
				'sort_order' => array('num', 0),
				'sort_order_cat' => array('num', 0),
				'cities_id' => array('num', null)
	);

  /* Black magic dispell */
  protected function _magic_sort_order(Array $data) { }

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->join("location_cities_data", "service_companies.cities_id = location_cities_data.id", array("city_name" => 'name'));
		$select->where("location_cities_data.languages_id = service_companies_data.languages_id");
	}

    public function updatePositions($category)
    {
        self::$_db->beginTransaction();
        self::$_db->query("SET @p := 0");
        self::$_db->query("UPDATE ".DB_EX_NAME.".service_companies SET sort_order_cat = @p := @p + 2 WHERE service_companies_categories_id = {$category} ORDER BY sort_order_cat;");
        self::$_db->query("SET @p := 0");
        self::$_db->query("UPDATE ".DB_EX_NAME.".service_companies SET sort_order = @p := @p + 2 ORDER BY sort_order;");
        self::$_db->commit();
    }

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->join("location_cities_data", "service_companies.cities_id = location_cities_data.id", array("city_name" => 'name'));
		$select->joinLeft("social_organizations_data", "service_companies.social_organizations_id = social_organizations_data.id AND social_organizations_data.languages_id = service_companies_data.languages_id", array('social_organization_name' => 'name'));
		$select->where("location_cities_data.languages_id = service_companies_data.languages_id");
	}

}