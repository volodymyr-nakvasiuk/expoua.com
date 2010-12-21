<?PHP
Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_Organizers extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('organizers', 'organizers_data');

	protected $_db_tables_join_by = array(array('organizers.id', 'id'));

	protected $_allowed_cols_array = array(
		array('id', 'active', 'cities_id', 'global_sync_id'),
		array('id', 'languages_id', 'name', 'address', 'postcode', 'phone', 'fax', 'email',
			'web_address', 'description', 'cont_pers_name', 'cont_pers_phone', 'cont_pers_email')
	);

	protected $_select_list_cols_array = array(array('id', 'active'), array('name'));

	protected $_sort_col = array('id' => 'DESC');

	public function insertEntry(Array $data) {
		$res = parent::insertEntry($data);
		
		if ($res == 1 && $this->allowSync == true) {
			$data['id'] = $this->getLastInsertId();
			$data['sync_cities_id'] = $data['cities_id'];
			
			$this->_AddSyncQueueTask(Sync_Base::TYPE_ADD, $data);
		}
		
		return $res;
	}
	
	public function updateEntry($id, Array $data, Array $extraParams = array()) {
		$res = parent::updateEntry($id, $data, $extraParams);
		
		// != -1 из-за того, что запись сама по себе может и не быть обновлена, но связанные данные обновятся
		if ($res != -1 && $this->allowSync == true) {
			$data = $this->getEntry($id, $extraParams);
			if (!empty($data)) {
				$data['sync_cities_id'] = $data['cities_id'];						
				$this->_AddSyncQueueTask(Sync_Base::TYPE_UPDATE, $data);
			}
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
			$data['sync_cities_id'] = $data['cities_id'];
						
			$this->_AddSyncQueueTask(Sync_Base::TYPE_DELETE, $data);
		}
		
		return $res;
	}
	
	/**
	 * Возвращает список выбранных общественных организаций
	 *
	 * @param int $org_id
	 * @return array
	 */
	public function getSelectedSocOrgsList($org_id) {
		$select = self::$_db->select();

		$select->from('ExpoPromoter_Opt.social_organizations_to_organizers',
			array('id' => 'social_organizations_id', 'social_organizations_id'));
		$select->where("organizers_id = ?", $org_id);

		return self::$_db->fetchPairs($select);
	}

	/**
	 * Обновляет список общественных организаций, связанных с брендом
	 *
	 * @param int $org_id
	 * @param array $data
	 * @return array
	 */
	public function updateSocOrgs($org_id, Array $data) {
		$where = self::$_db->quoteInto("organizers_id = ?", intval($org_id));
		self::$_db->delete("ExpoPromoter_Opt.social_organizations_to_organizers", $where);

		$result = 0;

		foreach ($data as $el) {
			$row = array('organizers_id' => $org_id, 'social_organizations_id' => $el);
			$result += self::$_db->insert("ExpoPromoter_Opt.social_organizations_to_organizers", $row);
		}

		return $result;
	}

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->join("location_cities_data",
			"organizers.cities_id = location_cities_data.id", array("city_name" => 'name'));
		$select->where("location_cities_data.languages_id = organizers_data.languages_id");

		$select->join("location_cities", "organizers.cities_id = location_cities.id", array());
		$select->join("location_countries_data",
			"location_cities.countries_id = location_countries_data.id", array("country_name" => 'name'));
		$select->where("location_countries_data.languages_id = organizers_data.languages_id");
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->join("location_cities", "organizers.cities_id = location_cities.id", array('countries_id'));
		$select->join("location_cities_data",
			"organizers.cities_id = location_cities_data.id", array("city_name" => 'name'));
		$select->where("location_cities_data.languages_id = organizers_data.languages_id");
	}

	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		parent::_SqlAddsWhere($select, $params);

		if (isset($params['countries_id'])) {
			$select->where("location_cities.countries_id ?", $params['countries_id']);
		}
	}

}