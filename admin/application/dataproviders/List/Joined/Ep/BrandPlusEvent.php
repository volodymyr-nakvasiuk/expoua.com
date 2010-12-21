<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_BrandPlusEvent extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('view_event_all_subquery', 'brands_data');

	protected $_db_tables_join_by = array(array('view_event_all_subquery.brands_id', 'id'));

	protected $_allowed_cols_array = array(
		array('id', 'active', 'brands_id', 'expocenters_id', 'brands_categories_id', 'organizers_id',
			'date_from', 'date_to', 'cities_id', 'countries_id', 'brand_dead', 'languages_id',
			'event_free_tickets', 'is_last'),
		array('name', 'languages_id')
	);

	protected $_select_list_cols_array =
		array(array('id', 'active', 'brands_id', 'organizers_id', 'date_from', 'date_to', 'cities_id', 'brand_dead'),
		array("name"));

	protected $_sort_col = array('id' => 'DESC');
	
	/**
	 * Флаг, указывающий нужно ли подсчитывать количество черновиков с таким же брендом
	 * для каждой записи
	 * @var boolean
	 */
	public $getBrandDraftsCnt = false;

	/**
	 * Переопределяем функцию и наворачиваем в ней много извращений чтобы выборка была очень быстрой
	 *
	 * @param int $results_num
	 * @param int $page
	 * @param array $extraParams
	 * @param array $sortBy
	 * @return array
	 */
	public function getList($results_num = null, $page = null, Array $extraParams = array(), Array $sortBy = array()) {
		$result = array();
		self::$_db->query("USE " . DB_EX_NAME);

		$select = self::$_db->select();

		$select->from("view_event_all_subquery");

		//В случае наличия дополнительных ограничивающих параметров, учитываем их
		if (sizeof($extraParams) >0) {
			$this->_SqlAddsWhere($select, $extraParams);
		}

		//Если нужен пейджинг, вводим ограничения
		if (!is_null($results_num) && !is_null($page)) {
			$page = intval($page);
			$results_num = intval($results_num);

			//Определяемся с общим количеством записей в таблице
			$select_count = clone $select;

			$select_count->reset(Zend_Db_Select::COLUMNS);
			$select_count->from('', new Zend_Db_Expr("COUNT(*)"));

			$number_of_rows = self::$_db->fetchOne($select_count);
			$number_of_pages = ceil($number_of_rows / $results_num);

			if ($page > $number_of_pages) {
				$page = $number_of_pages;
			}

			$result = array('page' => $page, 'pages' => $number_of_pages, 'rows' => $number_of_rows);

			$select->limitPage($page, $results_num);
		} else {
			$result = array('page' => 1, 'pages' => 1, 'rows' => 0);
		}

		$result['sort_by'] = null;

		//Если нужно, сортируем результат
		if (sizeof($sortBy)>0) {
			$result['sort_by'] = $this->_SqlAddsSort($select, $sortBy);
		}

		if (is_null($result['sort_by']) && sizeof($this->_sort_col) > 0) {
			$result['sort_by'][key($this->_sort_col)] = current($this->_sort_col);
			foreach ($this->_sort_col as $key => $el) {
				$select->order($this->_db_tables_array[0] . "." . $key . " " . $el);
			}
		}

		$from_suqbquery = new Zend_Db_Expr("(" . $select->__toString() . ")");

		//Zend_Debug::dump($from_suqbquery);

		$select = null;
		$select = self::$_db->select();

		$select->from(array("view_event_all_subquery" => "__replace_me__"), $this->_select_list_cols_array[0]);
		$this->_SqlAddsList($select);

		//Учитываем язык
		$select->where("brands_data.languages_id = ?", $extraParams['languages_id']);

		$select_sql = $select->__toString();
		$select_sql = str_replace("`__replace_me__`", $from_suqbquery, $select_sql);

		//Zend_Debug::dump($select_sql);
		//return array();

		$result['data'] = self::$_db->fetchAll($select_sql);

		self::$_db->query("USE " . DB_NAME);

		return $result;
	}

	public function insertEntry(Array $data) {
		return 0;
	}

	public function updateEntry($id, Array $data, Array $extraParams = array()) {
		return 0;
	}

	public function deleteEntry(Array $id = array(), Array $extraParams = array()) {
		return 0;
	}

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->join("location_cities_data", "view_event_all_subquery.cities_id=location_cities_data.id", array("city_name" => "name"))
			->join("location_countries_data", "view_event_all_subquery.countries_id=location_countries_data.id", array("country_name" => "name"))
			->join("organizers_data", "view_event_all_subquery.organizers_id=organizers_data.id", array("organizer_name" => 'name'))
			->join("events_common", "view_event_all_subquery.id = events_common.id", array('premium' => 'show_list_logo'))
			//->join("statistic.events_counter", "view_event_all_subquery.id = events_counter.id", array('view_cnt', 'req_cnt', 'breq_cnt', 'redir_cnt'))
			->joinLeft("expocenters_data", "view_event_all_subquery.expocenters_id=expocenters_data.id AND expocenters_data.languages_id=brands_data.languages_id", array("expocenter_name" => 'name'))
			->where("location_cities_data.languages_id=brands_data.languages_id")
			->where("location_countries_data.languages_id=brands_data.languages_id")
			->where("organizers_data.languages_id=brands_data.languages_id")
//			->where("expocenters_data.languages_id=brands_data.languages_id");

      ->from('', 
        array(
          'galleries' => new Zend_DB_Expr(
            '(SELECT COUNT(*) FROM ExpoPromoter_Opt.events_galleries AS eg WHERE eg.events_id = events_common.id)'
          )
        )
      )
      
      ->from('', 
        array(
          'tickets' => new Zend_DB_Expr(
            '(
               SELECT COUNT(*) 
               FROM ExpoPromoter_Opt.events_tickets AS et
               WHERE et.events_id = events_common.id
            )'
          )
        )
      );

		if ($this->getBrandDraftsCnt == true) {
			$select->from('', array('drafts_brand_cnt' => new Zend_DB_Expr(
				'(SELECT COUNT(*) FROM ExpoPromoter_Opt.events_drafts WHERE brands_id = view_event_all_subquery.brands_id)')));
		}
	}
	
	
	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		$select->join("events_common", "view_event_all_subquery.id = events_common.id", array('premium' => 'show_list_logo'));
	}


	protected function _SqlAddsSort(Zend_Db_Select &$select, Array $sort) {
		$db_table_save = $this->_db_tables_array;
		$allowed_cols_save = $this->_allowed_cols_array;

		$result = null;

		$this->_allowed_cols_array = array($this->_allowed_cols_array[0]);
		$this->_db_tables_array = array($this->_db_tables_array[0]);
		$result = parent::_SqlAddsSort($select, $sort);

		$this->_db_tables_array = $db_table_save;
		$this->_allowed_cols_array = $allowed_cols_save;

		return $result;
	}

	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		$db_table_save = $this->_db_tables_array;
		$allowed_cols_save = $this->_allowed_cols_array;

		$this->_allowed_cols_array = array($this->_allowed_cols_array[0]);
		$this->_db_tables_array = array($this->_db_tables_array[0]);
		parent::_SqlAddsWhere($select, $params);

		//Если производится поиск по имени, добавляем связанную таблицу
		if (!empty($params['name'])) {
			$select->join("brands_data", "view_event_all_subquery.brands_id=brands_data.id", array())
							->where("brands_data.languages_id = ?", $params['languages_id'])
							->where("brands_data.name " . $params['name']);
		}

		//Фильтр по премиум-пакету
		if (!empty($params['premium'])) {
			$select->join("events_common", "view_event_all_subquery.id = events_common.id", array());
			$select->where("events_common.show_list_logo ?", $params['premium']);
		}

		//Выводим только без будущих событий
		if (!empty($params['_only_wfe'])) {
			$select->where("brands_id NOT IN (SELECT brands_id FROM ExpoPromoter_Opt.events WHERE date_to>DATE_SUB(NOW(), INTERVAL 10 DAY))");
		}

		$this->_db_tables_array = $db_table_save;
		$this->_allowed_cols_array = $allowed_cols_save;
	}

}