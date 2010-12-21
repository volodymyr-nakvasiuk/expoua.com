<?PHP
Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_Brands extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('brands', 'brands_data');

	protected $_db_tables_join_by = array(array('brands.id', 'id'));

	protected $_allowed_cols_array = array(
		array('id', 'organizers_id', 'brands_categories_id', 'dead', 'email_requests', 'global_sync_id'),
		array('id', 'languages_id', 'name', 'name_extended')
	);

	protected $_select_list_cols_array = array(
		array('id', 'brands_categories_id', 'dead'),
		array('name')
	);

	protected $_sort_col = array('id' => 'DESC');

	public function insertEntry(Array $data) {
		$res = parent::insertEntry($data);
		
		if ($res == 1 && $this->allowSync == true) {
			$data['id'] = $this->getLastInsertId();
			// По стране организатора принимается решение о синхронизации
			$org = self::_DP("List_Joined_Ep_Organizers")->getEntry($data['organizers_id']);
			$data['sync_cities_id'] = $org['cities_id'];
			
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
				// По стране организатора принимается решение о синхронизации
				$org = self::_DP("List_Joined_Ep_Organizers")->getEntry($data['organizers_id']);
				$data['sync_cities_id'] = $org['cities_id'];
				
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
			// По стране организатора принимается решение о синхронизации
			$org = self::_DP("List_Joined_Ep_Organizers")->getEntry($data['organizers_id']);
			$data['sync_cities_id'] = $org['cities_id'];
						
			$this->_AddSyncQueueTask(Sync_Base::TYPE_DELETE, $data);
		}
		
		return $res;
	}
	
	/**
	 * Возвращает список выбранных категорий бренда
	 *
	 * @param int $brand_id
	 * @return array
	 */
	public function getSelectedCategoriesList($brand_id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_Opt.brands_to_categories", array('id' => 'brands_categories_id', 'brands_categories_id'));
		$select->where("brands_id = ?", $brand_id);

		return self::$_db->fetchPairs($select);
	}

	/**
	 * Возвращает список выбранных подкатегорий бренда
	 *
	 * @param int $brand_id
	 * @return array
	 */
	public function getSelectedSubCategoriesList($brand_id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_Opt.brands_to_subcategories", array('id' => 'subcategories_id', 'subcategories_id'));
		$select->where("brands_id = ?", $brand_id);

		return self::$_db->fetchPairs($select);
	}

	/**
	 * Обновляет список выбранных подкатегорий для бренда
	 *
	 * @param int $brand_id
	 * @param array $data
	 * @return int
	 */
	public function updateCategories($brand_id, Array $data) {
		$where = self::$_db->quoteInto("brands_id = ?", intval($brand_id));
		self::$_db->delete("ExpoPromoter_Opt.brands_to_categories", $where);

		$result = 0;

		foreach ($data as $el) {
			$row = array('brands_id' => $brand_id, 'brands_categories_id' => $el);

			$result += self::$_db->insert("ExpoPromoter_Opt.brands_to_categories", $row);
		}

		return $result;
	}

	/**
	 * Обновляет список выбранных подкатегорий бренда
	 *
	 * @param int $brand_id
	 * @param array $data
	 * @return array
	 */
	public function updateSubCategories($brand_id, Array $data) {
		$where = self::$_db->quoteInto("brands_id = ?", intval($brand_id));
		self::$_db->delete("ExpoPromoter_Opt.brands_to_subcategories", $where);

		$result = 0;

		foreach ($data as $el) {
			$row = array('brands_id' => $brand_id, 'subcategories_id' => $el);

			$result += self::$_db->insert("ExpoPromoter_Opt.brands_to_subcategories", $row);
		}

		return $result;
	}

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->join("organizers", "organizers.id = brands.organizers_id", array());
		$select->join("organizers_data", "organizers.id = organizers_data.id", array("organizer_name" => 'name'));
		$select->join("location_cities", "organizers.cities_id = location_cities.id", array());
		$select->join("location_countries_data", "location_cities.countries_id = location_countries_data.id", array('country_name' => 'name'));
		$select->where("organizers_data.languages_id = brands_data.languages_id");
		$select->where("location_countries_data.languages_id = brands_data.languages_id");
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->join("organizers_data", "brands.organizers_id = organizers_data.id", array("organizer_name" => 'name'));
		$select->where("organizers_data.languages_id = brands_data.languages_id");
	}

	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		parent::_SqlAddsWhere($select, $params);

		if (isset($params['countries_id'])) {
			$select->where("location_cities.countries_id ?", $params['countries_id']);
		}
	}

}