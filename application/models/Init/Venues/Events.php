<?php
class Init_Venues_Events extends Init_VenuesTab {

	static $tab_name = 'events';

	protected function pageAction(){
		$filter = array(
			'p'=>$this->_actionId,
			'expocenters_id'=>$this->_venueId,
			'limit'=>5,
		);
		$grid = new Crud_Grid_Events(null, $filter);
		return $grid->getData();
	}
}
