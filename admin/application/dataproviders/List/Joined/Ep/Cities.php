<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_Cities extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('location_cities', 'location_cities_data');

	protected $_db_tables_join_by = array(
		array('location_cities.id', 'id')
	);

	protected $_allowed_cols_array = array(
		array('id', 'countries_id', 'geonameid', 'active', 'extended', 'global_sync_id'),
		array('id', 'languages_id', 'name')
	);

	protected $_select_list_cols_array = array(
		array('id', 'active', 'countries_id', 'geonameid', 'extended'),
		array('name')
	);

	protected $_sort_col = array('id' => 'DESC');
	
	protected $_prepare_cols = array(
		'geonameid' => array('num', null)
	);

	public function insertEntry(Array $data) {
		$res = parent::insertEntry($data);
		
		if ($res == 1 && $this->allowSync == true) {
			$data['id'] = $this->getLastInsertId();
			// Синхронизируем только города в разрешенной стране
			$data['sync_cities_id'] = $data['id'];
			
			$this->_AddSyncQueueTask(Sync_Base::TYPE_ADD, $data);
		}
		
		return $res;
	}
	
	public function updateEntry($id, Array $data, Array $extraParams = array()) {
		$res = parent::updateEntry($id, $data, $extraParams);
		
		if ($res == 1 && $this->allowSync == true) {
			$data = $this->getEntry($id, $extraParams);
			// Синхронизируем только города в разрешенной стране
			$data['sync_cities_id'] = $data['id'];
				
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
			// Синхронизируем только города в разрешенной стране
			$data['sync_cities_id'] = $data['id'];
						
			$this->_AddSyncQueueTask(Sync_Base::TYPE_DELETE, $data);
		}
		
		return $res;
	}

	/**
	 * Производит поиск английского названия города в geo-базе
	 * Если город найден, обновляет его geonameid
	 * Возвращает количество обновленных 0 или 1 в общем случае
	 * 
	 * @param $cities_id
	 * @return int
	 */
	public function findCityInGeoDB($cities_id) {
		$query = "UPDATE ExpoPromoter_Opt.location_cities AS c,
ExpoPromoter_Opt.location_cities_data AS cd,
ExpoPromoter_Opt.location_countries AS cnt,
ExpoPromoter_cms.geo_cities_min AS gc
SET c.geonameid = gc.geonameid
WHERE cd.languages_id = 2 AND c.id = cd.id AND cnt.id = c.countries_id AND
cnt.code = gc.countryCode AND cd.name = gc.name AND c.id = ?";
		
		return self::$_db->query($query, array($cities_id))->rowCount();
	}
	
	/**
	 * Возвращает полную доступную гео-информацию о городе
	 * 
	 * @param $cities_id
	 * @param $languages_id
	 * @return array
	 */
	public function getGeoNameInfo($cities_id, $languages_id) {
		$query = "SELECT c.id, cd.name AS city, cntd.name AS country, cnt.code,
gc.geonameid, gc.name AS geocityname, gc.latitude, gc.longitude
FROM ExpoPromoter_Opt.location_cities AS c
JOIN ExpoPromoter_Opt.location_countries AS cnt ON (c.countries_id = cnt.id)
JOIN ExpoPromoter_Opt.location_cities_data AS cd ON (c.id = cd.id)
JOIN ExpoPromoter_Opt.location_countries_data AS cntd ON (cnt.id = cntd.id)
LEFT JOIN ExpoPromoter_cms.geo_cities_min AS gc ON (gc.geonameid = c.geonameid)
WHERE c.id = ? AND cd.languages_id = cntd.languages_id AND cd.languages_id = ?";
		
		return self::$_db->fetchRow($query, array($cities_id, $languages_id));
	}
	
	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->join("location_countries_data",
			"location_cities.countries_id = location_countries_data.id", array("country_name" => 'name'));
		$select->where("location_countries_data.languages_id = location_cities_data.languages_id");
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->join("location_countries_data",
			"location_cities.countries_id = location_countries_data.id", array("country_name" => 'name'));
		$select->where("location_countries_data.languages_id = location_cities_data.languages_id");
	}


	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		parent::_SqlAddsWhere($select, $params);

		if (isset($params['all'])) {
		} else {
			$select->where("`extended` = 0");
		}
	}

}