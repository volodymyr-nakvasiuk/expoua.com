<?php
class Init_Statistics {

	protected $_eventsCount = false;
	
	public function __construct(){
	}

	protected function _setEventsCount(){
		//$db = Db_Events();
		$this->_eventsCount = 364555+rand(1, 1000);
	}

	public function getEventsCount(){
		if($this->_eventsCount === false){
			$this->_setEventsCount();
		}
		return $this->_eventsCount;
	}
}
?>