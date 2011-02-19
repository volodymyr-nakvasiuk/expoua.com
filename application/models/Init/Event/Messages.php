<?php
class Init_Event_Messages extends Init_EventTab {

	static $tab_name = 'messages';

	protected function pageAction(){
		return array('data'=>array(1));
	}
}
