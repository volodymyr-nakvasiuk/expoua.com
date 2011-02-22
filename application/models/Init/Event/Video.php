<?php
class Init_Event_Video extends Init_EventTab {

	static $tab_name = 'video';

	protected function pageAction(){
		$filter = array(
			'p'=>$this->_actionId,
			'id_expo_event'=>$this->_eventId,
			'limit'=>5,
		);
		$grid = new Crud_Grid_Videos(null, $filter);
		return $grid->getData();
	}

	protected function cardAction(){
		$grid = new Crud_Grid_Video(null, array('id'=>$this->_actionId, 'limit'=>1));
		$data = $grid->getData();
		if (isset($data['data'][0])){
			$data['data'] = $data['data'][0];
		}
		else {
			$data = array('data'=>array(), 'error'=>true);
		}
		return $data;
	}
}
