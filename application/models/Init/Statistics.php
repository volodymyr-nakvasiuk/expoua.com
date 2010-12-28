<?php
class Init_Statistics {

	protected $_eventsCount = false;
	protected $_companiesCount = false;
	
	public function __construct(){
	}

	protected function _setEventsCount(){
		$db = new Db_Brands();
		$this->_eventsCount = $db->select()->getRowCount();
	}

	public function getEventsCount(){
		if($this->_eventsCount === false){
			$this->_setEventsCount();
		}
		return $this->_eventsCount;
	}

	protected function _setCompaniesCount(){
		$db = new Db_Companies();
		$this->_companiesCount = $db->select()->getRowCount();
	}

	public function getCompaniesCount(){
		if($this->_companiesCount === false){
			$this->_setCompaniesCount();
		}
		return $this->_companiesCount;
	}
}
?>