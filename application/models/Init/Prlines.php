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
		$types = array();
		for($i=1; $i<=$this->_count; $i++) $types[] = "'".$this->_typeName.$i."'";
		$tool = new Tools_Banner($types, $this->_module, $this->_categoryId, $this->_count);
		$this->_bannersData = $tool->getData();
		$tool->updateStat();

		$eventsIds = array();
		$bannerData = array();
		foreach ($this->_bannersData as $banner){
			$eventsIds[] = $banner['pline_events_id'];
			$bannerData[$banner['pline_events_id']] = $banner;
		}

		if ($eventsIds){
			$grid = new Crud_Grid_Event(null, array('id'=>$eventsIds, 'limit'=>$this->_count));
			$this->_data = $grid->getData();
		}
		else {
			$this->_data = array('data'=>array());
		}

		foreach ($this->_data['data'] as &$data){
			$data['bannerData'] = $bannerData[$data['id']];
		}
	}
}
