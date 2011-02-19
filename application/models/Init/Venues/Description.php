<?php
class Init_Venues_Description extends Init_VenuesTab {

	static $tab_name = 'description';
	protected $_content = false;

	public function __construct($venue_id, $tab, $tab_action, $tab_id, $content=false){
		parent::__construct($venue_id, $tab, $tab_action, $tab_id);
		$this->_content = $content;
	}
	
	protected function pageAction(){
		return array('data'=>array('content'=>$this->_content));
	}
}
