<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_Requests extends List_Abstract {

	protected $_allowed_cols = array('id', 'type', 'parent', 'child', 'viewed', 'date_add');

	protected $_db_table = "ExpoPromoter_Opt.requests";

	protected $_select_list_cols = array('id', 'type', 'parent', 'child', 'viewed', 'date_add');

	protected $_sort_col = array('id' => 'DESC');

	public function getEntryData($id) {
		$select = self::$_db->select();

		$select->from("requests_data", array('type', 'value'), "ExpoPromoter_Opt");
		$select->where("requests_data.requests_id = ?", $id);

		return self::$_db->fetchPairs($select);
	}

	protected function _SqlAddsList(Zend_Db_Select &$select) {
	  $select->from("", array('ip' => new Zend_Db_Expr("INET_NTOA(host)")), "ExpoPromoter_Opt");
		$select->join("languages", "requests.languages_id = languages.id", array('lang_name' => 'name'), "ExpoPromoter_Opt");
	}


  protected function _SqlAttachFields(Zend_Db_Select &$select) {
/*	  $select->from("", array('company_name' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'name')")), "ExpoPromoter_Opt");
	  
	  $select->from("", array('contact_person' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'contact_person')")), "ExpoPromoter_Opt");
	  
	  $select->from("", array('position' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'position')")), "ExpoPromoter_Opt");
	  
	  $select->from("", array('city' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'city')")), "ExpoPromoter_Opt");
	  
	  $select->from("", array('phone' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'phone')")), "ExpoPromoter_Opt");
	  
	  $select->from("", array('fax' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'fax')")), "ExpoPromoter_Opt");
	  
	  $select->from("", array('url' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'url')")), "ExpoPromoter_Opt");
	  
	  $select->from("", array('email' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'email')")), "ExpoPromoter_Opt");
	  
	  $select->from("", array('address' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'address')")), "ExpoPromoter_Opt");
	  
/*	  $select->from("", array('purpose2' => new Zend_Db_Expr("(
      SELECT qt.name 
      FROM ExpoPromoter_Opt.requests_data
      LEFT JOIN ExpoPromoter.expoua_ru_query_types AS qt ON requests_data.value = qt.id
      WHERE requests_id = requests.id AND `type` = 'purpose'
)")), "ExpoPromoter_Opt");*/
	  
/*	  $select->from("", array('message' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'message')")), "ExpoPromoter_Opt");
	  
	  $select->from("", array('details' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'details')")), "ExpoPromoter_Opt");
	  
	  $select->from("", array('S1' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'S1')")), "ExpoPromoter_Opt");
	  
	  $select->from("", array('S2' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'S2')")), "ExpoPromoter_Opt");
	  
	  $select->from("", array('S3' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'S3')")), "ExpoPromoter_Opt");
	  
	  $select->from("", array('check1' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'check1')")), "ExpoPromoter_Opt");
	  
	  $select->from("", array('check2' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'check2')")), "ExpoPromoter_Opt");
	  
	  $select->from("", array('check3' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'check3')")), "ExpoPromoter_Opt");
	  
	  $select->from("", array('check4' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'check4')")), "ExpoPromoter_Opt");
	  
	  $select->from("", array('check5' => new Zend_Db_Expr("(SELECT IFNULL(value, '-') FROM ExpoPromoter_Opt.requests_data WHERE requests_id = requests.id AND `type` = 'check5')")), "ExpoPromoter_Opt");
  */}


/*
  protected function _SqlAddsDebug(Zend_Db_Select &$select) {
    echo $select->__toString();
  }
*/


	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {

		if (isset($params['type'])) {
			//echo $params['type']->__toString();
			switch ($params['type']) {
				case "exhibitionExtraInfoRequest":
				case "exhibitionParticipationRequest":
				case "exhibitionCatalogAdvertRequest":
				case "exhibitionAdvertSpreadRequest":
				case "exhibitionRemoteAttendanceRequest":
					$select->join("organizers_data", "requests.parent = organizers_data.id", array('name'), "ExpoPromoter_Opt");
					$select->join("events", "requests.child = events.id", array('date_from', 'date_to'), "ExpoPromoter_Opt");
					$select->join("brands_data", "events.brands_id = brands_data.id", array('brand_name' => 'name'), "ExpoPromoter_Opt");
					$select->join("location_cities", "events.cities_id = location_cities.id", array(), "ExpoPromoter_Opt");
					$select->join("location_cities_data", "events.cities_id = location_cities_data.id", array('city_name' => 'name'), "ExpoPromoter_Opt");
					$select->join("location_countries_data", "location_cities.countries_id = location_countries_data.id", array('country_name' => 'name'), "ExpoPromoter_Opt");
					$select->where("organizers_data.languages_id = ?", $params['languages_id']);
					$select->where("brands_data.languages_id = organizers_data.languages_id AND brands_data.languages_id = location_cities_data.languages_id AND brands_data.languages_id = location_countries_data.languages_id");
					break;
				case "serviceCompanyRequest":
					$select->join("service_companies_data", "requests.parent = service_companies_data.id", array('name'), "ExpoPromoter_Opt");
					$select->where("service_companies_data.languages_id = ?", $params['languages_id']);
					break;
				case "exhibitionCenterRequest":
					$select->join("expocenters_data", "requests.parent = expocenters_data.id", array('name'), "ExpoPromoter_Opt");
					$select->where("expocenters_data.languages_id = ?", $params['languages_id']);
					break;
			}
		} elseif (isset($params['organizers_id'])) {
			//Для админки организаторов
			$select->join("events", "requests.child = events.id", array('date_from', 'date_to'), "ExpoPromoter_Opt");
			$select->join("events_common", "events.id = events_common.id", array('show_list_logo'), "ExpoPromoter_Opt");
			$select->join("brands_data", "events.brands_id = brands_data.id", array('brand_name' => 'name'), "ExpoPromoter_Opt");
			$select->joinLeft("requests_data", "requests.id = requests_data.requests_id AND requests_data.type = 'purpose'", array('purpose' => 'value'), 'ExpoPromoter_Opt');
			$select->where("brands_data.languages_id = ?", $params['languages_id']);

			$params['parent'] = $params['organizers_id'];

			if (isset($params['brands_id'])) {
				if ($params['brands_id'] instanceof Zend_Db_Expr) {
					$select->where("events.brands_id ?", $params['brands_id']);
				}
			}

			if (isset($params['events_id'])) {
				if ($params['events_id'] instanceof Zend_Db_Expr) {
					$select->where("events.id ?", $params['events_id']);
				}
			}
		}

		parent::_SqlAddsWhere($select, $params);

		//Zend_Debug::dump($select->__toString());
	}

	protected function _SqlAddsSort(Zend_Db_Select &$select, Array $sort) {
		$res = parent::_SqlAddsSort($select, $sort);

		if (isset($sort['request_type'])) {
			$select->order("requests.type " . $sort['request_type']);
			$select->order("purpose " . $sort['request_type']);
			$res['request_type'] = $sort['request_type'];
		}
		if (isset($sort['exhibition'])) {
			$select->order("brand_name " . $sort['exhibition']);
			$res['exhibition'] = $sort['exhibition'];
		}
		//Zend_Debug::dump($select->__toString());

		return $res;
	}
}