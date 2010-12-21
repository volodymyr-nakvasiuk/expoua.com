<?PHP

require_once(PATH_DATAPROVIDERS . "/List/Joined/Abstract.php");

class List_Joined_Ep_Abstract extends List_Joined_Abstract {

	//В конструкторе добавляем ко всем таблицам имя БД
	public function __construct() {
		foreach ($this->_db_tables_array as &$el) {
			$el = DB_EX_NAME . "." . $el;
		}
	}

	public function insertEntry(Array $data) {
		self::$_db->query("USE " . DB_EX_NAME);
		$res = parent::insertEntry($data);
		self::$_db->query("USE " . DB_NAME);

		return $res;
	}

	public function updateEntry($id, Array $data, Array $extraParams = array()) {
		self::$_db->query("USE " . DB_EX_NAME);
		$res = parent::updateEntry($id, $data, $extraParams);
		self::$_db->query("USE " . DB_NAME);

		return $res;
	}

	public function getList($results_num = null, $page = null, Array $extraParams = array(), Array $sortBy = array()) {
		self::$_db->query("USE " . DB_EX_NAME);
		$res = parent::getList($results_num, $page, $extraParams, $sortBy);
		self::$_db->query("USE " . DB_NAME);

		return $res;
	}

	public function getEntry($id, Array $extraParams = array()) {
		self::$_db->query("USE " . DB_EX_NAME);
		$res = parent::getEntry($id, $extraParams);
		self::$_db->query("USE " . DB_NAME);

		return $res;
	}

	public function deleteEntry(Array $id = array(), Array $extraParams = array()) {
		self::$_db->query("USE " . DB_EX_NAME);
		$res = parent::deleteEntry($id, $extraParams);
		self::$_db->query("USE " . DB_NAME);

		return $res;
	}

	public function moveEntry($id, $relPos) {
		self::$_db->query("USE " . DB_EX_NAME);
		$res = parent::moveEntry($id, $relPos);
		self::$_db->query("USE " . DB_NAME);

		return $res;
	}


}