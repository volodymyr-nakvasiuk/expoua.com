<?php
class Init_Event_News extends Init_EventTab {

	static $tab_name = 'news';

	protected function pageAction(){
		$filter = array(
			'p'=>$this->_actionId,
			'brands_id'=>$this->_brandsId,
			'limit'=>5,
		);
		$grid = new Crud_Grid_EventNews(null, $filter);
		return $grid->getData();
	}

	protected function cardAction(){
		$grid = new Crud_Grid_EventOneNews(null, array('id'=>$this->_actionId, 'limit'=>1));
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
