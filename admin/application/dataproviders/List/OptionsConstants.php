<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_OptionsConstants extends List_Abstract {

	protected $_allowed_cols = array('id', 'code', 'value', 'description');

	protected $_db_table = "options_constants";

	protected $_select_list_cols = array('id', 'code', 'value', 'description');

	/**
	 * Возвращает значение конфигурационного параметра по его коду
	 *
	 * @param string $code
	 * @return string
	 */
	public function getValueByCode($code) {
		$select = self::$_db->select();

		$select->from($this->_db_table, array('value'));
		$select->where("code = ?", $code);
		$select->limit(1);

		return self::$_db->fetchOne($select);
	}

}