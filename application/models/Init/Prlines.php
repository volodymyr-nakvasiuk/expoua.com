<?php
class Init_Prlines{
	protected	$_typeName = 'pr_line';

	protected $_module = false;
	protected $_categoryId = false;
	protected $_count = false;

	protected $_bannersData = false;
	protected $_data = false;

	public function __construct($module=null, $categoryId=null, $count=4){
		$this->_module = $module;
		$this->_categoryId = $categoryId;
		$this->_count = $count;
	}

	public function getData(){
		if (!$this->_data) $this->_setData();
		return $this->_data;
	}

	protected function _setData() {
		$tool = new Tools_Banner($this->_typeName, $this->_module, $this->_categoryId, $this->_count);
		$this->_bannersData = $tool->getData();
		$tool->updateStat();

		$eventsIds = array();
		$bannerData = array();
		foreach ($this->_bannersData as $banner){
			$eventsIds[] = $banner['pline_events_id'];
			$bannerData[$banner['pline_events_id']] = $banner;
		}

		$grid = new Crud_Grid_Event(null, array('id'=>$eventsIds, 'limit'=>$this->_count));
		$this->_data = $grid->getData();

		foreach ($this->_data['data'] as &$data){
			$data['bannerData'] = $bannerData[$data['id']];
		}
	}
}
