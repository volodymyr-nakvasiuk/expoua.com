<?php
class Init_Index_Events {

	protected $_data   = false;
	protected $_limit  = '3';
	protected $_filter = array(
		'country'=>52
	);

	public function __construct($filter=false, $limit='3'){
		$this->_limit  = $limit;
		if ($filter) $this->_filter = $filter;
	}

	protected function _setData(){
		$grid = new Crud_Grid_Events(null, $this->_filter);
		$grid->setLimit($this->_limit);
		$this->_data = $grid->getData();
	}

	public function getData(){
		if (!$this->_data) $this->_setData();
		return $this->_data;
	}
}
