<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_Events extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('events', 'events_data', 'events_common', 'events_active');

	protected $_db_tables_join_by = array(
		array('events.id', 'id'),
		array('events.id', 'id'),
		array('events.id', 'id')
	);

	protected $_allowed_cols_array = array(
		array('id', 'brands_id', 'expocenters_id', 'cities_id', 'active', 'date_from', 'date_to',
			'free_tickets', 'global_sync_id'),
		array('id', 'languages_id', 'number', 'description', 'thematic_sections', 'work_time',
			'email', 'web_address', 'phone', 'fax', 'cont_pers_name', 'cont_pers_phone',
			'cont_pers_email', 'logo'),
		array('id', 'periods_id', 'partic_num', 'local_partic_num', 'foreign_partic_num',
			'visitors_num', 'local_visitors_num', 'foreign_visitors_num', 's_event_total',
			'show_list_logo', 'user_request_types', 'is_free', 'ticket_fee', 'url', 'deposit_buyer'),
		array('id', 'languages_id', 'active')
	);

	protected $_select_list_cols_array = array(
		array('id', 'date_from', 'date_to'),
		array('number'),
		array(),
		array('active')
	);

	protected $_sort_col = array('id' => 'DESC');

	protected $_prepare_cols = array(
		'expocenters_id' => array('num', null),
		'periods_id' => array('num', null),
		'is_free' => array('num', null)
	);

	public function insertEntry(Array $data) {
		$res = parent::insertEntry($data);
		
		if ($res == 1 && $this->allowSync == true) {
			$data['id'] = $this->getLastInsertId();
			// По стране организатора принимается решение о синхронизации
			$brand = self::_DP("List_Joined_Ep_Brands")->getEntry($data['brands_id']);
			$org = self::_DP("List_Joined_Ep_Organizers")->getEntry($brand['organizers_id']);
			$data['sync_cities_id'] = $org['cities_id'];
			
			$this->_AddSyncQueueTask(Sync_Base::TYPE_ADD, $data);
		}
		
		return $res;
	}
	
	public function updateEntry($id, Array $data, Array $extraParams = array()) {
		$res = parent::updateEntry($id, $data, $extraParams);
		
		if ($res == 1 && $this->allowSync == true) {
			$data = $this->getEntry($id, $extraParams);
			// По стране организатора принимается решение о синхронизации
			$brand = self::_DP("List_Joined_Ep_Brands")->getEntry($data['brands_id']);
			$org = self::_DP("List_Joined_Ep_Organizers")->getEntry($brand['organizers_id']);
			$data['sync_cities_id'] = $org['cities_id'];
				
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
			// По стране организатора принимается решение о синхронизации
			$brand = self::_DP("List_Joined_Ep_Brands")->getEntry($data['brands_id']);
			$org = self::_DP("List_Joined_Ep_Organizers")->getEntry($brand['organizers_id']);
			$data['sync_cities_id'] = $org['cities_id'];
						
			$this->_AddSyncQueueTask(Sync_Base::TYPE_DELETE, $data);
		}
		
		return $res;
	}

	/**
	 * Возвращает полный список дополнительных запросов
	 *
	 * @return array
	 */
	public function getRequestsList() {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_Opt.requests_additional", array('id', 'name'));
		$select->where("languages_id = ?", 1);

		return self::$_db->fetchAssoc($select);
	}

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->join("brands_data", "events.brands_id = brands_data.id", array("brand_name" => 'name'));
		$select->where("brands_data.languages_id = events_data.languages_id");
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->join("brands", "events.brands_id = brands.id", array("brands_categories_id", "organizers_id"));
		$select->join("brands_categories_data", "brands_categories_data.id = brands.brands_categories_id", array("brands_categories_name" => 'name'));
		$select->join("brands_data", "events.brands_id = brands_data.id", array("brand_name" => 'name', 'brand_name_extended' => 'name_extended'));
		$select->join("location_cities_data", "events.cities_id = location_cities_data.id", array("city_name" => 'name'));
		$select->joinLeft("expocenters_data", "expocenters_data.id = events.expocenters_id", array("expocenter_name" => 'name'));

		$select->where("brands_data.languages_id = events_data.languages_id");
		$select->where("brands_categories_data.languages_id = events_data.languages_id");
		$select->where("location_cities_data.languages_id = events_data.languages_id");
	}

	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		parent::_SqlAddsWhere($select, $params);

		if (!empty($params['name'])) {
			$select->where("brands_data.name " . $params['name']);
		}
	}
}