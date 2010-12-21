<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_Banners_PblStatClicks extends List_Abstract {

	protected $_allowed_cols = array('date_click', 'banners_id', 'publishers_id', 'clicker_ip', 'country_code', 'price');

	protected $_db_table = "ExpoPromoter_banners.pbl_stat_clicks";

	protected $_select_list_cols = array('date_click', 'banners_id', 'publishers_id', 'country_code', 'price');

	protected $_sort_col = array('date_click' => 'DESC');

	public function getList($results_num = null, $page = null, Array $extraParams = array(), Array $sortBy = array()) {
		$result = array();

		$select = self::$_db->select();

		$select->from($this->_db_table,
			array_merge($this->_select_list_cols, array('clicker_ip' => new Zend_Db_Expr("INET_NTOA(clicker_ip)"))));
		$this->_SqlAddsList($select);

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
				$select->order($this->_db_table . "." . $key . " " . $el);
			}
		}

		//Zend_Debug::dump($select->__toString());

		try {
			$result['data'] = self::$_db->fetchAll($select);
		} catch (Exception $e) {
			echo $e->getMessage();
		}

		return $result;
	}

	public function insertEntry(Array $data) {}
	public function updateEntry($id = null, Array $data, Array $extraParams = array()) {}
	public function deleteEntry(Array $id = array(), Array $extraParams = array()) {}

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$select->join("ExpoPromoter_banners.pbl_banners", "pbl_banners.id = pbl_stat_clicks.banners_id", array('banner_name' => 'name'));
	}

	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		parent::_SqlAddsWhere($select, $params);

		//Фильтр по дате
		if (isset($params['date_from'])) {
			$select->where("pbl_stat_clicks.date_click ?", $params['date_from']);
		}
		if (isset($params['date_to'])) {
			$select->where("pbl_stat_clicks.date_click ?", $params['date_to']);
		}
	}

}