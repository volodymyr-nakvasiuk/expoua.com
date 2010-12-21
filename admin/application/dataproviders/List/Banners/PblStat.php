<?PHP
class List_Banners_PblStat extends DataProviderAbstract {

	private $_db_table_shows     = "ExpoPromoter_banners.pbl_stat_shows";
	private $_db_table_clicks    = "ExpoPromoter_banners.pbl_stat_clicks";
	private $_db_table_banners   = "ExpoPromoter_banners.pbl_banners";
	private $_db_table_users     = "ExpoPromoter_banners.pbl_users";
	private $_db_table_languages = "ExpoPromoter_Opt.languages";
	private $_db_table_countries = "ExpoPromoter_Opt.location_countries_data";
	private $_db_table_b2c       = "ExpoPromoter_banners.pbl_banners_to_countries";

	private $_db_table_banners_cols = array('id', 'users_id', 'name', 'file_name');

	public function getList($results_num = null, $page = null, Array $extraParams = array(), Array $sortBy = array()) {
		$select_sq = self::$_db->select();
		$select = self::$_db->select();

		$select_sq->from(array('b' => $this->_db_table_banners), array('id', 'name', 'languages_id'));
		$select_sq->join(array('u' => $this->_db_table_users), "b.users_id = u.id", array('user_login' => 'login'));

		if (!empty($extraParams)) {
			$this->_SqlAddsWhere($select_sq, $extraParams);
		}

		$select_sq->order("b.id DESC");

		//Если нужен пейджинг, вводим ограничения
		if (!is_null($results_num) && !is_null($page)) {
			$page = intval($page);
			$results_num = intval($results_num);

			//Определяемся с общим количеством записей в таблице
			$select_count = clone $select_sq;

			$select_count->reset(Zend_Db_Select::COLUMNS);
			$select_count->from('', new Zend_Db_Expr("COUNT(*)"));

			//Zend_Debug::dump($select_count->__toString());

			$number_of_rows = self::$_db->fetchOne($select_count);
			$number_of_pages = ceil($number_of_rows / $results_num);

			if ($page > $number_of_pages) {
				$page = $number_of_pages;
			}

			$result = array('page' => $page, 'pages' => $number_of_pages, 'rows' => $number_of_rows);

			$select_sq->limitPage($page, $results_num);
		} else {
			$result = array('page' => 1, 'pages' => 1, 'rows' => 0);
		}

		$select_clicks = self::$_db->select();
		$select_clicks->from($this->_db_table_clicks, array(new Zend_Db_Expr("COUNT(*)")));
		$select_clicks->where("banners_id = sq.id");

		//Фильтр по дате
		if (isset($extraParams['date_start'])) {
			$select_clicks->where("date_click ?", $extraParams['date_start']);
			$select->where("s.date_show ?", $extraParams['date_start']);
		}
		if (isset($extraParams['date_end'])) {
			$select_clicks->where("date_click ?", $extraParams['date_end']);
			$select->where("s.date_show ?", $extraParams['date_end']);
		}


		if (isset($extraParams['country_name'])) {
			if ($extraParams['country_name'] instanceof Zend_Db_Expr) {
				$select->where("cd.name ?", $extraParams['country_name']);
			} else {
				$select->where("cd.name = ?", $extraParams['country_name']);
			}
		}

		if (isset($extraParams['language'])) {
			if ($extraParams['language'] instanceof Zend_Db_Expr) {
				$select->where("l.name ?", $extraParams['language']);
			} else {
				$select->where("l.name = ?", $extraParams['language']);
			}
		}


		$query_clicks = "(" . $select_clicks->__toString() . ")";

		$select_clicks->reset(Zend_Db_Select::COLUMNS);
		$select_clicks->from("", array(new Zend_Db_Expr("SUM(price)")));
		$query_clicks_price = "(" . $select_clicks->__toString() . ")";

		$select->from(array('sq' => $select_sq),
			array('id', 'name', 'user_login', 'clicks' => new Zend_Db_Expr($query_clicks), 'price' => new Zend_Db_Expr($query_clicks_price)));
		$select->join(array('s' => $this->_db_table_shows), "sq.id = s.banners_id",
			array('shows' => new Zend_Db_Expr("SUM(s.shows)")));
		$select->join(array('l' => $this->_db_table_languages), "sq.languages_id = l.id", array('language' => 'name'));
		$select->joinLeft(array('bc' => $this->_db_table_b2c), "sq.id = bc.banners_id", array());

		$lang = Zend_Registry::get('language_id');

		$select->joinLeft(array('cd' => $this->_db_table_countries), "bc.countries_id = cd.id AND cd.languages_id = $lang", array('country_name' => 'name'));

		//При установке этого флага производится проверка баннера на наличие показов
		if (isset($extraParams['_check_banners_presence'])) {
			$select->from("",
				array('shows_day' => new Zend_Db_Expr("(SELECT COUNT(*) FROM " . $this->_db_table_shows . " WHERE banners_id=sq.id AND date_show>=DATE_SUB(CURDATE(), INTERVAL 1 DAY))")));
		}

		$select->group("s.banners_id");
		$select->order("sq.id DESC");

		/*
		if (isset($extraParams['country_name'])) {
		echo "<pre>" . $select->__toString() . "</pre>";
		}
		*/
		//echo $select->__toString();

		try {
			$result['data'] = self::$_db->fetchAssoc($select);
		} catch (Exception $e) {
			echo $e->getMessage();
		}

		return $result;
	}

	public function getTotal(Array $extraParams = array()) {
		$select = self::$_db->select();
		$select_sq = self::$_db->select();

		$select_sq->from(array('sqs' => $this->_db_table_shows), array(new Zend_Db_Expr('SUM( sqs.shows ) ')));
		$select_sq->join(array('sqb' => $this->_db_table_banners), "sqb.id = sqs.banners_id", array());
		//Не у всех сайтов есть свои пользователи, поэтому связь включаем только в случае фильтра по пользователю, иначе в выборке будут отсутсвовать статистика по сайтам без пользователей
		if (isset($extraParams['users_id'])) {
			$select_sq->where("sqb.users_id = b.users_id");
		}

		//Фильтр по дате
		if (isset($extraParams['date_start'])) {
			$select_sq->where("sqs.date_show ?", $extraParams['date_start']);
			$select->where("c.date_click ?", $extraParams['date_start']);
		}
		if (isset($extraParams['date_end'])) {
			$select_sq->where("sqs.date_show ?", $extraParams['date_end']);
			$select->where("c.date_click ?", $extraParams['date_end']);
		}

		$select->from(array('b' => $this->_db_table_banners), array(
		'clicks' => new Zend_Db_Expr("COUNT(c.date_click)"),
		'price' => new Zend_Db_Expr("SUM(c.price)"),
		'shows' => "(" . $select_sq->__toString() . ")"));

		$select->join(array('c' => $this->_db_table_clicks), "c.banners_id = b.id", array());

		if (!empty($extraParams)) {
			$this->_SqlAddsWhere($select, $extraParams);
		}

		//Zend_Debug::dump($select->__toString());

		return self::$_db->fetchRow($select);
	}

	public function getEntryGeneral($id, Array $params = array()) {
		$select = self::$_db->select();
		$select->from(array('s' => $this->_db_table_shows), array(
		'date_show',
		'shows' => new Zend_Db_Expr("SUM(s.shows)"),
		'clicks' => new Zend_Db_Expr("(SELECT COUNT(*) FROM ExpoPromoter_banners.pbl_stat_clicks WHERE date_click>s.date_show AND date_click<DATE_ADD(s.date_show, INTERVAL 1 DAY) AND banners_id=s.banners_id)")));

		$select->where("s.banners_id = ?", $id);
		$select->group("s.date_show");

		return self::$_db->fetchAll($select);
	}

	public function getEntryModules($id, Array $params = array()) {
		$select = self::$_db->select();
		$select->from(array('s' => $this->_db_table_shows),
		array('shows' => new Zend_Db_Expr("SUM(s.shows)")));

		$select->joinLeft("ExpoPromoter_banners.pbl_modules_data", "pbl_modules_data.id = s.modules_id AND pbl_modules_data.languages_id = " . Zend_Registry::get("language_id"), array('module_name' => 'name'));

		$select->where("s.banners_id = ?", $id);
		$select->group("s.modules_id");

		return self::$_db->fetchAll($select);
	}

	public function getEntrySites($id, Array $params = array()) {
		$select = self::$_db->select();
		$select->from(array('s' => $this->_db_table_shows),
		array('shows' => new Zend_Db_Expr("SUM(s.shows)")));

		$select->join("ExpoPromoter_Opt.sites_data", "sites_data.id = s.sites_id", array('site_name' => 'name'));

		$select->where("sites_data.languages_id = ?", Zend_Registry::get('language_id'));
		$select->where("s.banners_id = ?", $id);
		$select->group("s.sites_id");

		return self::$_db->fetchAll($select);
	}

	public function getEntryClicks($id, Array $params = array()) {
		$select = self::$_db->select();
		$select->from(array('c' => $this->_db_table_clicks),
		array('date_click', 'price', 'country_code', 'ip' => new Zend_Db_Expr("INET_NTOA(clicker_ip)")));

		$select->joinLeft("ExpoPromoter_Opt.sites_data", "sites_data.id=c.publishers_id AND sites_data.languages_id = " . Zend_Registry::get('language_id'), array('site_name' => 'name'));
		$select->joinLeft("ExpoPromoter_banners.countries_code_to_name", "countries_code_to_name.code = c.country_code", array('country_name' => 'name'));

		$select->where("c.banners_id = ?", $id);
		$select->order("c.date_click DESC");

		return self::$_db->fetchAll($select);
	}

	private function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		$this->_prepareDataArray($params, $this->_db_table_banners_cols);

		foreach ($params as $key => $el) {
			if ($el instanceof Zend_Db_Expr) {
				$select->where("b." . $key . " ?", $el);
			} else {
				$select->where("b." . $key . " = ?", $el);
			}
		}
	}

	private function _prepareDataArray(Array &$data, &$allowed_cols) {
		foreach ($data as $key => $el) {
			if (!in_array($key, $allowed_cols)) {
				unset($data[$key]);
			}
		}
	}

}