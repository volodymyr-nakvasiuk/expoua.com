<?php
class Init_OnlineExpo{
	protected $_showRooms = false;
	protected $_data = false;

	public function __construct(){
	}

	public function getData(){
		if (!$this->_data) $this->_setData();
		return $this->_data;
	}

	protected function _setData() {
		$this->_setShowRooms();

		foreach ($this->_data as &$showRoom){
			$grid = new Crud_Grid_OnlinePlaces(null, array('limit'=>'all', 'showrooms_id'=>$showRoom['id']));
			$data = $grid->getDataWithRenderValues();
			$showRoom['places'] = $data['data'];
		}
	}

	protected function _setShowRooms() {
		$grid = new Crud_Grid_OnlineShowrooms(null, array('limit'=>'all'));
		$data = $grid->getData();
		$this->_data = $data['data'];
	}
}
