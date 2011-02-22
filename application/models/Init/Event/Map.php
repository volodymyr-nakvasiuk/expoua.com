<?php
class Init_Event_Map extends Init_EventTab {

	static $tab_name = 'map';
	protected $_event = false;

	public function __construct($brands_id, $event_id, $tab, $tab_action, $tab_id, $event=array()){
		parent::__construct($brands_id, $event_id, $tab, $tab_action, $tab_id);
		$this->_event = $event;
	}

	protected function pageAction(){
		$data = array();
		if ($this->_event['latitude'] && $this->_event['longitude']){
			$data = array(
				'lat'=>$this->_event['latitude'],
				'lng'=>$this->_event['longitude'],
				'title'=>htmlentities($this->_event['expocenter'], ENT_QUOTES, 'utf-8'),
				'address'=>htmlentities($this->_event['address'], ENT_QUOTES, 'utf-8'),
			);
		}
		return array('data'=>$data);
	}
}
