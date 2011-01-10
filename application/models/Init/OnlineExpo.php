<?php
class Init_OnlineExpo{
	protected $_showRooms = false;
	protected $_data = false;
	protected $_filter = false;

	public function __construct($filter){
		if (!is_array($filter)) $filter = array('category'=>$filter);
		$this->_filter = $filter;
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
		$filter = $this->_filter;
		$filter['limit'] = 'all';
		$grid = new Crud_Grid_OnlineShowrooms(null, $filter);
		$data = $grid->getData();
		$this->_data = $data['data'];
	}
}
