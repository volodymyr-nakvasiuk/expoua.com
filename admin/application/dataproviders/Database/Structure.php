<?PHP

class Database_Structure extends DataproviderAbstract {

	public function getTablesList() {
		return self::$_db->listTables();
	}

	public function getTableStructure($table) {
		$req = self::$_db->query("DESCRIBE " . self::$_db->quoteIdentifier($table));

		return $req->fetchAll();
	}

	public function getCreateTableSQL($table) {
		$req = self::$_db->query("SHOW CREATE TABLE " . self::$_db->quoteIdentifier($table));
		$req->setFetchMode(Zend_Db::FETCH_NUM);

		return $req->fetch();
	}

	public function createTable($sql) {
		self::$_db->query($sql);
	}

}