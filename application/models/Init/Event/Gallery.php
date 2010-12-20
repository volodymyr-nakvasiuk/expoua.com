<?php
class Init_Event_Gallery extends Init_EventTab {

	static $tab_name = 'photo';

	protected function pageAction(){
		$filter = array(
			'events_id'=>$this->_eventId,
			'limit'=>'all',
		);
		$grid = new Crud_Grid_EventGallery(null, $filter);
		return $grid->getData();
	}
}
