<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_EventsDrafts extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('events_drafts', 'events_drafts_data');

	protected $_db_tables_join_by = array(array('events_drafts.id', 'id'));

	protected $_allowed_cols_array = array(
		array('status', 'type', 'brands_id', 'expocenters_id', 'cities_id', 'date_from',
			'date_to', 'periods_id', 'partic_num', 'local_partic_num', 'foreign_partic_num',
			'visitors_num', 'local_visitors_num', 'foreign_visitors_num', 's_event_total',
			'users_operators_id', 'events_id', 'comments', 'brand_organizers_id',
			'brand_categories_id', 'premium', 'is_free', 'ticket_fee'),
		array('id', 'languages_id', 'number', 'description', 'thematic_sections', 'work_time',
			'email', 'web_address', 'phone', 'fax', 'cont_pers_name', 'cont_pers_phone',
			'cont_pers_email', 'logo', 'brand_name_new', 'brand_name_extended_new')
	);

	protected $_select_list_cols_array = array(
		array('id', 'status', 'type', 'date_from', 'date_to', 'users_operators_id', 'date_add', 'comments', 'premium'),
		array('number', 'brand_name_new')
	);

	protected $_sort_col = array('id' => 'DESC');

	protected $_prepare_cols = array(
		'expocenters_id' => array('num', null),
		'periods_id' => array('num', null),
		'events_id' => array('num', null),
		'brands_id' => array('num', null),
		'brand_organizers_id' => array('num', null),
		'brand_categories_id' => array('num', null),
		'is_free' => array('num', null)
	);

	public function getDistinctDraftsBrandsList($oid) {
		$select = self::$_db->select();

		$select->distinct();
		$select->from("ExpoPromoter_Opt.events_drafts", array('brands_id', 'brands_id'));
		$select->where("users_operators_id = ?", $oid);

		return self::$_db->fetchPairs($select);
	}

	public function getSelectedSubCategoriesList($id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_Opt.events_drafts_to_subcategories", array('brands_subcategories_id'));
		$select->join("ExpoPromoter_Opt.brands_subcategories_data",
			"events_drafts_to_subcategories.brands_subcategories_id = brands_subcategories_data.id",
			array('name'));

		$select->where("brands_subcategories_data.languages_id =1");
		$select->where("events_drafts_to_subcategories.events_drafts_id = ?", $id);

		//echo $select->__toString();

		return self::$_db->fetchPairs($select);
	}

	public function updateSubCategories($id, Array $data) {
		$where = self::$_db->quoteInto("events_drafts_id = ?", intval($id));
		self::$_db->delete("ExpoPromoter_Opt.events_drafts_to_subcategories", $where);

		$result = 0;

		foreach ($data as $el) {
			$row = array('events_drafts_id' => $id, 'brands_subcategories_id' => $el);
			$result += self::$_db->insert("ExpoPromoter_Opt.events_drafts_to_subcategories", $row);
		}

		return $result;
	}

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->joinLeft("brands", "brands.id = events_drafts.brands_id", array('organizers_id'));

		$select->joinLeft("brands_data", "brands_data.id = events_drafts.brands_id AND events_drafts_data.languages_id = brands_data.languages_id", array('brand_name' => 'name'));
		$select->joinLeft("expocenters_data", "expocenters_data.id = events_drafts.expocenters_id AND events_drafts_data.languages_id = expocenters_data.languages_id", array('expocenter_name' => 'name'));

		$select->joinLeft("organizers_data", "brands.organizers_id = organizers_data.id AND organizers_data.languages_id = events_drafts_data.languages_id", array("organizer_name" => 'name'));

		$select->join("location_cities", "location_cities.id = events_drafts.cities_id", array());
		$select->join("location_cities_data", "location_cities_data.id = events_drafts.cities_id AND events_drafts_data.languages_id = location_cities_data.languages_id", array('city_name' => 'name'));
		$select->join("location_countries_data", "location_countries_data.id = location_cities.countries_id AND events_drafts_data.languages_id = location_countries_data.languages_id", array('country_name' => 'name'));

		$select->join("users_operators", "users_operators.id = events_drafts.users_operators_id", array("login", 'operator_type' => 'type'));
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->joinLeft("brands_data", "events_drafts.brands_id = brands_data.id AND brands_data.languages_id = events_drafts_data.languages_id", array("brand_name" => 'name', 'brand_name_extended' => 'name_extended'));
		$select->joinLeft("organizers_data", "events_drafts.brand_organizers_id = organizers_data.id AND organizers_data.languages_id = events_drafts_data.languages_id", array("organizer_name" => 'name'));
		$select->joinLeft("expocenters_data", "expocenters_data.id = events_drafts.expocenters_id AND expocenters_data.languages_id = events_drafts_data.languages_id", array("expocenter_name" => 'name'));

		$select->join("location_cities_data", "events_drafts.cities_id = location_cities_data.id", array("city_name" => 'name'));

		$select->where("location_cities_data.languages_id = events_drafts_data.languages_id");
	}

	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		parent::_SqlAddsWhere($select, $params);

		if (isset($params['login'])) {
			$select->where("users_operators.login ?", $params['login']);
		}

		if (isset($params['operator_type'])) {
			$select->where("users_operators.type ?", $params['operator_type']);
		}

		if (isset($params['organizers_id']) && $params['organizers_id'] instanceof Zend_Db_Expr) {
			$select->where("brands.organizers_id ?", $params['organizers_id']);
			//$select->where("brands.organizers_id = ?", $params['organizers_id']);
		}

	}
}