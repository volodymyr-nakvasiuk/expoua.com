<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_Operators extends List_Abstract {

	protected $_allowed_cols = array('id', 'active', 'super', 'type', 'login', 'passwd',
		'name_fio', 'phone', 'email', 'position', 'organizer_manual_name', 'organizers_id',
		'service_companies_id', 'user_languages_id', 'create_time');

	protected $_db_table = "ExpoPromoter_Opt.users_operators";

	protected $_select_list_cols = array('id', 'active', 'super', 'login', 'organizers_id',
		'create_time');

	protected $_prepare_cols = array(
		'organizers_id' => array('num', null),
		'service_companies_id' => array('num', null),
		'user_languages_id' => array('num', 2)
	);

	protected $_sort_col = array('id' => 'DESC');

	public function getEntryByLogin($login) {
		$select = self::$_db->select();

		$select->from($this->_db_table);
		$select->where("login = ?", $login);

		return self::$_db->fetchRow($select);
	}

	public function updateOperatorCountries($oid, Array $counries_ids) {
		$where = self::$_db->quoteInto("operators_id = ?", $oid);
		self::$_db->delete("ExpoPromoter_Opt.users_operators_to_countries", $where);

		$result = 0;
		foreach ($counries_ids as $el) {
			$row = array('operators_id' => $oid, 'countries_id' => intval($el));
			$result += self::$_db->insert("ExpoPromoter_Opt.users_operators_to_countries", $row);
		}

		return $result;
	}

	public function getOperatorCountries($oid) {
		$select = self::$_db->select();
		$select->from('ExpoPromoter_Opt.users_operators_to_countries', array('countries_id', 'countries_id'));
		$select->where("operators_id = ?", $oid);

		return self::$_db->fetchPairs($select);
	}

	public function getOperatorCountriesByName($operator) {
		$select = self::$_db->select();
		$select->from('ExpoPromoter_Opt.users_operators_to_countries', array('countries_id'));
		$select->join($this->_db_table, "users_operators_to_countries.operators_id=users_operators.id", array());
		$select->where("users_operators.login = ?", $operator);

		return self::$_db->fetchCol($select);
	}

	public function updateOrganizerResources($id, Array $resources_ids) {
		$where = self::$_db->quoteInto("users_organizers_id = ?", $id);
		self::$_db->delete("ExpoPromoter_Opt.users_organizers_to_resources", $where);

		$result = 0;
		foreach ($resources_ids as $el) {
			$row = array('users_organizers_id' => $id, 'acl_resources_id' => intval($el));
			$result += self::$_db->insert("ExpoPromoter_Opt.users_organizers_to_resources", $row);
		}

		return $result;
	}

	public function updateOrganizerBrands($id, Array $brands_ids) {
		$where = self::$_db->quoteInto("users_organizers_id = ?", $id);
		self::$_db->delete("ExpoPromoter_Opt.users_organizers_to_brands", $where);

		$result = 0;
		foreach ($brands_ids as $el) {
			$row = array('users_organizers_id' => $id, 'brands_id' => intval($el));
			$result += self::$_db->insert("ExpoPromoter_Opt.users_organizers_to_brands", $row);
		}

		return $result;
	}

	public function getOrganizerResources($id) {
		$select = self::$_db->select();
		$select->from("ExpoPromoter_Opt.users_organizers_to_resources", array('acl_resources_id', 'acl_resources_id'));
		$select->where("users_organizers_to_resources.users_organizers_id = ?", $id);

		return self::$_db->fetchPairs($select);
	}

	public function getOrganizerBrands($id) {
		$select = self::$_db->select();
		$select->from("ExpoPromoter_Opt.users_organizers_to_brands", array('brands_id', 'brands_id'));
		$select->where("users_organizers_id = ?", $id);

		return self::$_db->fetchPairs($select);
	}

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$select->joinLeft("ExpoPromoter_Opt.organizers", "organizers.id=users_operators.organizers_id", array());
		$select->joinLeft("ExpoPromoter_Opt.organizers_data", "organizers_data.id=organizers.id AND organizers_data.languages_id=1", array('organizer_name' => 'name'));

		$select->joinLeft("ExpoPromoter_Opt.location_cities", "organizers.cities_id=location_cities.id", array());
		$select->joinLeft("ExpoPromoter_Opt.location_cities_data", "location_cities.id=location_cities_data.id AND location_cities_data.languages_id=1", array('city_name' => 'name'));

		$select->joinLeft("ExpoPromoter_Opt.location_countries", "location_countries.id=location_cities.countries_id", array());
		$select->joinLeft("ExpoPromoter_Opt.location_countries_data", "location_countries.id = location_countries_data.id AND location_countries_data.languages_id=1", array('country_name' => 'name'));

		$select->joinLeft("ExpoPromoter_Opt.service_companies_data", "service_companies_data.id=users_operators.service_companies_id AND service_companies_data.languages_id=1", array('servicecomp_name' => 'name'));
	}

	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		parent::_SqlAddsWhere($select, $params);

		if (isset($params['cities_id'])) {
			$select->where("location_cities.id ?", $params['cities_id']);
		}
		if (isset($params['countries_id'])) {
			$select->where("location_countries.id ?", $params['countries_id']);
		}
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		$select->joinLeft("ExpoPromoter_Opt.organizers_data", "organizers_data.id=users_operators.organizers_id AND organizers_data.languages_id=1", array('organizer_name' => 'name'));

		$select->joinLeft("ExpoPromoter_Opt.service_companies_data", "service_companies_data.id=users_operators.service_companies_id AND service_companies_data.languages_id=1", array('servicecomp_name' => 'name'));
	}
}