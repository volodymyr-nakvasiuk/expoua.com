<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

abstract class List_Joined_Abstract extends List_Abstract implements List_Interface {

	//Необходимо переинициализировать переменные для текущей области видимости
	protected $_db_table = null;
	protected $_allowed_cols = array();
	protected $_select_list_cols = array();
	protected $_prepare_cols = array();

	/**
	 * Массив, содержащий названия таблиц БД, с которыми производится работа
	 * Очередность указания очень важна.
	 * Сперва данные должны добавляться в главную таблицу, после чего в подчиненные.
	 *
	 * @var array
	 */
	protected $_db_tables_array = array();

	/**
	 * Массив, содержащий список столбцов по которым осуществляется join
	 * Формат: элементами массива являются массивы, содержащие 2 элемента - названия строк, по которым производится соединение
	 * Количество элементов должно быть на один меньше, чем количество таблиц
	 *
	 * @var array
	 */
	protected $_db_tables_join_by = array();

	/**
	 * Массив, содержит допустимые имена столбцов для каждой из таблиц
	 *
	 * @var array
	 */
	protected $_allowed_cols_array = array();

	/**
	 * Массив, содержащий столбцы, которые необходимо извлечь при выводе списка
	 *
	 * @var array
	 */
	protected $_select_list_cols_array = array();

	/**
	 * Идентификатор последней добавленной записи если таблица имеет автоинкремент
	 *
	 * @var int
	 */
	protected $_last_insert_id = null;

	public function insertEntry(Array $data) {

		$entry_id = null;
		//$this->_last_insert_id = 0;
		$joined_by_index = 0;

		self::$_db->beginTransaction();

		foreach ($this->_db_tables_array as $key_el => $this->_db_table) {
			$joined_by_index++;

			$this->_allowed_cols = $this->_allowed_cols_array[$key_el];

			$res = parent::insertEntry($data);

			if ($res != 1) {
				self::$_db->rollBack();
				return $res;
			}

			if (is_null($entry_id)) {
				$entry_id = $this->getLastInsertId();
				//Подставляем последний сгенерированный индекс в поле, название которого берем из массива соединительных элементов таблиц

				//Может не существовать если в объединении используется только одна таблица
				if (isset($this->_db_tables_join_by[$joined_by_index-1][1])) {
					$col_name = $this->_db_tables_join_by[$joined_by_index-1][1];

					$data[$col_name] = $entry_id;
					//$this->_last_insert_id = $entry_id;
				}
			}

		}

		self::$_db->commit();

		return $res;
	}

	public function insertLanguageData($data) {
		self::$_db->beginTransaction();

		$res = 0;

		foreach ($this->_db_tables_array as $key_el => $this->_db_table) {
			if (in_array('languages_id', $this->_allowed_cols_array[$key_el])) {
				$this->_allowed_cols = $this->_allowed_cols_array[$key_el];
				$res = parent::insertEntry($data);
				if ($res != 1) {
					self::$_db->rollBack();
					return $res;
				}
			}
		}

		self::$_db->commit();

		return $res;
	}

	public function updateEntry($id, Array $data, Array $extraParams = array()) {

		$result = 0;

		self::$_db->beginTransaction();

		foreach ($this->_db_tables_array as $key_el => $this->_db_table) {
			$this->_allowed_cols = $this->_allowed_cols_array[$key_el];
			$res = parent::updateEntry($id, $data, $extraParams);
			$result = max($res, $result);
		}

		if ($result == 1) {
			self::$_db->commit();
		} else {
			self::$_db->rollBack();
		}

		return $result;
	}

	public function getList($results_num = null, $page = null, Array $extraParams = array(), Array $sortBy = array()) {

		$this->_db_table = $this->_db_tables_array[0];
		$this->_allowed_cols = $this->_allowed_cols_array[0];
		$this->_select_list_cols = $this->_select_list_cols_array[0];

		return parent::getList($results_num, $page, $extraParams, $sortBy);
	}

	public function getEntry($id, Array $extraParams = array()) {

		$this->_db_table = $this->_db_tables_array[0];
		$this->_allowed_cols = $this->_allowed_cols_array[0];

		return parent::getEntry($id, $extraParams);
	}

	public function deleteEntry(Array $id = array(), Array $extraParams = array()) {
		$this->_db_table = $this->_db_tables_array[0];
		$this->_allowed_cols = $this->_allowed_cols_array[0];

		return parent::deleteEntry($id, $extraParams);
	}

	public function moveEntry($id, $relPos) {
		$this->_db_table = $this->_db_tables_array[0];
		$this->_allowed_cols = $this->_allowed_cols_array[0];

		return parent::moveEntry($id, $relPos);
	}

	public function getLastInsertId() {
		if (is_null($this->_last_insert_id)) {
			$this->_last_insert_id = self::$_db->lastInsertId();
		}

		return $this->_last_insert_id;
	}

	/**
	 * Переопределяем функцию для поддержки объединенных таблиц
	 *
	 * @param array $cols
	 */
	public function addColsToList(Array $cols) {
		foreach ($cols as $c) {
			foreach ($this->_allowed_cols_array as $a_key => $a) {
				if (in_array($c, $a)) {
					$this->_select_list_cols_array[$a_key][] = $c;
					break;
				}
			}
		}
	}

	protected function _SqlAddsList(Zend_Db_Select &$select) {

		foreach ($this->_db_tables_join_by as $key=>$tbl_join_by) {
			$select->join($this->_db_tables_array[$key+1],
				$tbl_join_by[0] . " = " . $this->_db_tables_array[$key+1] . "." . $tbl_join_by[1], $this->_select_list_cols_array[$key+1]);
		}

	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {

		foreach ($this->_db_tables_join_by as $key=>$tbl_join_by) {
			$select->join($this->_db_tables_array[$key+1],
				$tbl_join_by[0] . " = " . $this->_db_tables_array[$key+1] . "." . $tbl_join_by[1]);
		}

	}

	protected function _SqlAddsSort(Zend_Db_Select &$select, Array $sort) {
		$db_table_save = $this->_db_table;
		$allowed_cols_save = $this->_allowed_cols;

		$result = null;

		foreach ($this->_db_tables_array as $key => $this->_db_table) {
			$this->_allowed_cols = $this->_allowed_cols_array[$key];
			$tmp = parent::_SqlAddsSort($select, $sort);

			if (!is_null($tmp)) {
				$result = $tmp;
				break;
			}
		}

		$this->_db_table = $db_table_save;
		$this->_allowed_cols = $allowed_cols_save;

		return $result;
	}

	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		$db_table_save = $this->_db_table;
		$allowed_cols_save = $this->_allowed_cols;

		foreach ($this->_db_tables_array as $key => $this->_db_table) {
			$this->_allowed_cols = $this->_allowed_cols_array[$key];
			parent::_SqlAddsWhere($select, $params);
		}

		$this->_db_table = $db_table_save;
		$this->_allowed_cols = $allowed_cols_save;
	}
}