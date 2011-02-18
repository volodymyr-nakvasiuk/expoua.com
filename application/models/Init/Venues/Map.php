<?php
class Init_Venues_Map extends Init_VenuesTab {

	static $tab_name = 'map';
	protected $_venue = false;

	public function __construct($venue_id, $tab, $tab_action, $tab_id, $venue=array()){
		parent::__construct($venue_id, $tab, $tab_action, $tab_id);
		$this->_venue = $venue;
	}

	protected function pageAction(){
		$data = array();
		if ($this->_venue['latitude'] && $this->_venue['longitude']){
			$data = array(
				'lat'=>$this->_venue['latitude'],
				'lng'=>$this->_venue['longitude'],
				'title'=>htmlentities($this->_venue['name'], ENT_QUOTES, 'utf-8'),
				'address'=>htmlentities($this->_venue['address'], ENT_QUOTES, 'utf-8'),
			);
		}
		return array('data'=>$data);
	}
}
