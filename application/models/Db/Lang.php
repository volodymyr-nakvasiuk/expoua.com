<?php
class Db_Lang extends ArOn_Db_Table {
	static $globalLangId = 1;
	public $langId = 1;
	public $langName = 'languages_id';

	static function getInstance($className = false,$debug = false){
		unset(self::$_instance[$className]);
		return parent::getInstance($className, $debug);
	}

	public function __construct($config = array(), $definition = null) {
		$this->langId = self::$globalLangId;
		self::$globalLangId = 1;
		parent::__construct($config, $definition);
	}

	public function insert(array $data){
		$data[$this->langName] = $this->langId;
		return parent::insert($data);
	}

	public function update(array $data, $where){
		$langWhere = $this->langName.'='.$this->langId;
		$where = $where?('('.$where.') AND ('.$langWhere.')'):$langWhere;
		return parent::update($data, $where);
	}

	public function delete($where) {
		//$langWhere = $this->langName.'='.$this->langId;
		//$where = $where?('('.$where.') AND ('.$langWhere.')'):$langWhere;
		return parent::delete($where);
	}

	public function getRowById($id) {
		$tmpPrimary = $this->_primary;
		$this->_primary = is_array($this->_primary)?array_merge($this->_primary, array($this->langName)):array(1=>$this->_primary, 2=>$this->langName);
		$id = is_array($id)?array_merge($id, array($this->langId)):array($id, $this->langId);
		$result =  parent::getRowById($id);
		$this->_primary = $tmpPrimary;
		return $result;
	}

	public function getRowByParam($where = null) {
		$langWhere = $this->langName.'='.$this->langId;
		$where = $where?('('.$where.') AND ('.$langWhere.')'):$langWhere;
		return parent::getRowByParam($where);
	}

	protected function _fetch(Zend_Db_Table_Select $select){
		foreach ($select->getPart('from') as $table=>$rule){
			if ($table==$this->_name || (class_exists($table)&&is_subclass_of($table,'Db_Lang'))){
				$select->where($table.'.'.$this->langName.'='.$this->langId);
			}
		}
		return parent::_fetch($select);
	}
}