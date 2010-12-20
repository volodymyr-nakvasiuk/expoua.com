<?php
class Init_Event_Description extends Init_EventTab {

	static $tab_name = 'description';
	protected $_content = false;

	public function __construct($brands_id, $event_id, $tab, $tab_action, $tab_id, $content=false){
		parent::__construct($brands_id, $event_id, $tab, $tab_action, $tab_id);
		$this->_content  = $content;
	}
	
	protected function pageAction(){
		return array('data'=>array('content'=>$this->_content));
	}
}
