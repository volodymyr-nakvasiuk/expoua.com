<?PHP
class List_Banners_Stat extends DataProviderAbstract {

	private $_db_table_shows = "ExpoPromoter_banners.stat_shows";
	private $_db_table_clicks = "ExpoPromoter_banners.stat_clicks";
	private $_db_table_plans = "ExpoPromoter_banners.plans";
	private $_db_table_companies = "ExpoPromoter_banners.companies";

	private $_db_table_plans_cols = array('id', 'companies_id', 'name');

	public function getList($results_num = null, $page = null, Array $extraParams = array(), Array $sortBy = array()) {
		$select_sq = self::$_db->select();

		$select_sq->from(array('p' => $this->_db_table_plans), array('id', 'name'));
		$select_sq->join(array('c' => $this->_db_table_companies), "p.companies_id = c.id",
			array('company_name' => 'name'));

		if (!empty($extraParams)) {
			$this->_SqlAddsWhere($select_sq, $extraParams);
		}

		$select_sq->order("p.id DESC");

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
		$select_clicks->where("plans_id = sq.id");
		$query_clicks = "(" . $select_clicks->__toString() . ")";

		$select = self::$_db->select();
		$select->from(array('sq' => $select_sq),
			array('id', 'name', 'company_name', 'clicks' => new Zend_Db_Expr($query_clicks)));
		$select->join(array('s' => $this->_db_table_shows), "sq.id = s.plans_id",
			array('shows' => new Zend_Db_Expr("SUM(s.shows)")));

		//$select->joinLeft(array('c' => $this->_db_table_clicks), "c.plans_id = sq.id",
		//	array('clicks' => new Zend_Db_Expr("COUNT(DISTINCT c.date_click)")));

		$select->group("s.plans_id");
		$select->order("sq.id DESC");

		//Zend_Debug::dump($select->__toString());

		try {
			$result['data'] = self::$_db->fetchAssoc($select);
		} catch (Exception $e) {
			echo $e->getMessage();
		}

		return $result;
	}

	public function getEntryGeneral($id, Array $params = array()) {
		$select = self::$_db->select();
		$select->from(array('s' => $this->_db_table_shows),
			array('date_show', 'shows' => new Zend_Db_Expr("SUM(s.shows)")));

		$select->where("s.plans_id = ?", $id);
		$select->group("s.date_show");

		return self::$_db->fetchAll($select);
	}

	public function getEntryModules($id, Array $params = array()) {
		$select = self::$_db->select();
		$select->from(array('s' => $this->_db_table_shows),
			array('shows' => new Zend_Db_Expr("SUM(s.shows)")));

		$select->joinLeft("ExpoPromoter_banners.modules", "modules.id = s.modules_id", array('module_name' => 'name'));

		$select->where("s.plans_id = ?", $id);
		$select->group("s.modules_id");

		return self::$_db->fetchAll($select);
	}

	public function getEntryPublishers($id, Array $params = array()) {
		$select = self::$_db->select();
		$select->from(array('s' => $this->_db_table_shows),
			array('shows' => new Zend_Db_Expr("SUM(s.shows)")));

		$select->join("ExpoPromoter_banners.publishers", "publishers.id = s.publishers_id", array('publisher_name' => 'name'));

		$select->where("s.plans_id = ?", $id);
		$select->group("s.publishers_id");

		return self::$_db->fetchAll($select);
	}

	public function getEntryClicks($id, Array $params = array()) {
		$select = self::$_db->select();
		$select->from(array('c' => $this->_db_table_clicks),
			array('date_click', 'ip' => new Zend_Db_Expr("INET_NTOA(clicker_ip)")));

		$select->join("ExpoPromoter_Opt.languages", "languages.id=c.langs_id", array('lang_name' => 'name'));
		$select->join("ExpoPromoter_banners.publishers", "publishers.id=c.publishers_id", array('publisher_name' => 'name'));

		$select->where("c.plans_id = ?", $id);
		$select->order("c.date_click ASC");

		return self::$_db->fetchAll($select);
	}

	private function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		$this->_prepareDataArray($params, $this->_db_table_plans_cols);

		foreach ($params as $key => $el) {
			if ($el instanceof Zend_Db_Expr) {
				$select->where("p." . $key . " ?", $el);
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