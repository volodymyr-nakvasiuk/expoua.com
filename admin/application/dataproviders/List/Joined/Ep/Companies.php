<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_Companies extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('companies', 'companies_data', 'companies_active');

	protected $_db_tables_join_by = array(array('companies.id', 'id'), array('companies.id', 'id'));

	protected $_allowed_cols_array = array(
		array('cities_id', 'email', 'web_address', 'phone', 'fax', 'postcode', 'local_languages_id', 'logo', 'date_modify'),
		array('id', 'languages_id', 'name', 'description', 'address'),
		array('id', 'languages_id', 'active'));

	protected $_select_list_cols_array = array(
		array('id', 'date_modify'),
		array('name'),
		array('active'));

	protected $_sort_col = array('id' => 'DESC');

	protected $_prepare_cols = array(
		'local_languages_id' => array('num', null),
		'cities_id' => array('num', null)
	);

	/**
	 * Возвращает список событий, которые связанны с данной компанией
	 *
	 * @param int $id
	 * @return array
	 */
	public function getEventsList($id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_Opt.companies_to_events", array('stand_num'));
		$select->join("ExpoPromoter_Opt.events", "events.id=companies_to_events.events_id", array('id', 'date_from', 'date_to'));
		$select->join("ExpoPromoter_Opt.brands_data", "brands_data.id=events.brands_id", array('name'));
		$select->where("companies_to_events.companies_id = ?", $id);
		$select->where("brands_data.languages_id = ?", Zend_Registry::get("language_id"));

		$select->join("ExpoPromoter_Opt.location_cities", "location_cities.id = events.cities_id", array());
		$select->join("ExpoPromoter_Opt.location_cities_data", "location_cities_data.id = events.cities_id", array('city_name' => 'name'));
		$select->join("ExpoPromoter_Opt.location_countries_data", "location_countries_data.id = location_cities.countries_id", array('country_name' => 'name'));

		$select->where("location_cities_data.languages_id = location_countries_data.languages_id AND location_countries_data.languages_id = brands_data.languages_id");

		return self::$_db->fetchAll($select);
	}

	/**
	 * Обновляет список выставок, с которыми связана компания
	 *
	 * @param int $id
	 * @param array $events
	 * @return int
	 */
	public function updateEventsList($id, Array $events) {
		$where = self::$_db->quoteInto("companies_id = ?", $id);
		self::$_db->delete("ExpoPromoter_Opt.companies_to_events", $where);

		$result = 0;
		$dub = array();

		foreach ($events as $el) {

			//Исключаем дубликаты
			if (!in_array($el['id'], $dub)) {
				$row = array('companies_id' => $id, 'events_id' => $el['id'], 'stand_num' => $el['stand_num']);
				//Zend_Debug::dump($row);
				$result += self::$_db->insert("ExpoPromoter_Opt.companies_to_events", $row);
			}

			$dub[] = $el['id'];
		}

		return $result;
	}

	/**
	 * Возвращает список категорий, которые связанны с данной компанией
	 *
	 * @param int $id
	 * @return array
	 */
	public function getLinkedCategoriesList($id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_Opt.companies_to_brands_categories", array());
		$select->join("ExpoPromoter_Opt.brands_categories_data", "brands_categories_data.id = companies_to_brands_categories.brands_categories_id", array('id', 'name'));
		$select->where("companies_to_brands_categories.companies_id = ?", $id);
		$select->where("brands_categories_data.languages_id = ?", Zend_Registry::get("language_id"));

		return self::$_db->fetchAssoc($select);
	}

	/**
	 * Обновляет список категорий, с которыми связана компания
	 *
	 * @param int $id
	 * @param array $events
	 * @return int
	 */
	public function updateLinkedCategoriesList($id, Array $events) {
		$where = self::$_db->quoteInto("companies_id = ?", $id);
		self::$_db->delete("ExpoPromoter_Opt.companies_to_brands_categories", $where);

		$result = 0;

		foreach ($events as $el) {
			$row = array('companies_id' => $id, 'brands_categories_id' => $el);
			$result += self::$_db->insert("ExpoPromoter_Opt.companies_to_brands_categories", $row);
		}

		return $result;
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->joinLeft("location_cities_data", "companies.cities_id = location_cities_data.id AND location_cities_data.languages_id = companies_data.languages_id", array("city_name" => 'name'));
	}
}