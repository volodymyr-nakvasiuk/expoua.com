<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_Expocenters extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('expocenters', 'expocenters_data');

	protected $_db_tables_join_by = array(array('expocenters.id', 'id'));

	protected $_allowed_cols_array = array(
		array('id', 'active', 'login', 'passwd', 'cities_id', 'longitude', 'latitude',
	    	'exhib_pav_num', 's_total', 's_closed', 's_opened', 's_total_netto',
	    	's_closed_netto', 's_opened_netto', 'global_sync_id'), 
		array('id', 'languages_id', 'name', 'address', 'postcode', 'phone', 'fax', 'email',
	    	'web_address', 'description', 'logo', 'image_map', 'image_plan', 'image_view',
	    	'date_updated')
	);

	protected $_select_list_cols_array = array(
		array('id', 'active', 'login', 'longitude', 'latitude'),
		array('name', 'contact_name')
	);

	protected $_prepare_cols = array(
	    'exhib_pav_num' => array('num', null),
	    's_total' => array('num', null),
	    's_closed' => array('num', null),
	    's_opened' => array('num', null),
	    's_total_netto' => array('num', null),
	    's_closed_netto' => array('num', null),
	    's_opened_netto' => array('num', null)
	);

	protected $_sort_col = array('id' => 'DESC');

	public function insertEntry(Array $data) {
		$res = parent::insertEntry($data);
		
		if ($res == 1 && $this->allowSync == true) {
			$data['id'] = $this->getLastInsertId();
			// По стране, которая опеределяется через город
			$data['sync_cities_id'] = $data['cities_id'];
			
			$this->_AddSyncQueueTask(Sync_Base::TYPE_ADD, $data);
		}
		
		return $res;
	}
	
	public function updateEntry($id, Array $data, Array $extraParams = array()) {
		$res = parent::updateEntry($id, $data, $extraParams);
		
		if ($res == 1 && $this->allowSync == true) {
			$data = $this->getEntry($id, $extraParams);
			// По стране, которая опеределяется через город
			$data['sync_cities_id'] = $data['cities_id'];
				
			$this->_AddSyncQueueTask(Sync_Base::TYPE_UPDATE, $data);
		}
		
		return $res;
	}
	
	public function deleteEntry(Array $id = array(), Array $extraParams = array()) {
		$data = array();
		if (sizeof($id) > 0) {
			$data = $this->getEntry($id[0], $extraParams);
		}
		
		$res = parent::deleteEntry($id, $extraParams);
		
		if ($res == 1 && !empty($data) && $this->allowSync == true) {
			// По стране, которая опеределяется через город
			$data['sync_cities_id'] = $data['cities_id'];
						
			$this->_AddSyncQueueTask(Sync_Base::TYPE_DELETE, $data);
		}
		
		return $res;
	}
	
	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->join("location_cities_data", "expocenters.cities_id = location_cities_data.id",
			array("city_name" => 'name'));
		$select->where("location_cities_data.languages_id = expocenters_data.languages_id");

		$select->join("location_cities", "expocenters.cities_id = location_cities.id", array());
		$select->join("location_countries_data", "location_cities.countries_id = location_countries_data.id",
			array("country_name" => 'name'));
		$select->where("location_countries_data.languages_id = expocenters_data.languages_id");
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->join("location_cities", "location_cities.id = expocenters.cities_id", array('countries_id'));
		$select->join("location_cities_data",
			"expocenters.cities_id = location_cities_data.id",
			array("city_name" => 'name'));
		$select->join("location_countries_data",
			"location_cities.countries_id = location_countries_data.id",
			array("country_name" => 'name'));

		$select->where("location_cities_data.languages_id = expocenters_data.languages_id");
		$select->where("location_countries_data.languages_id = expocenters_data.languages_id");
	}

	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		parent::_SqlAddsWhere($select, $params);

		if (isset($params['countries_id'])) {
			$select->where("location_cities.countries_id ?", $params['countries_id']);
		}
	}
}