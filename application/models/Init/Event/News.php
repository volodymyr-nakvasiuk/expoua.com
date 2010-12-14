<?php
class Init_Event_News {

	static $tab_name     = 'news';
	protected $_data     = false;
	protected $_brandsId  = false;
	protected $_isActive = false;
	protected $_action   = false;
	protected $_actionId = false;

	public function __construct($brands_id, $tab, $tab_action, $tab_id){
		$this->_brandsId  = $brands_id;
		$this->_isActive = (self::$tab_name==$tab);
		$this->_action   = $this->_isActive?$tab_action:TAB_DEFAULT_ACTION;
		$this->_actionId = $this->_isActive?$tab_id:TAB_DEFAULT_ID;
	}
	
	protected function _setData(){
		$this->_data = array(
			'active' => $this->_isActive,
			'action' => $this->_action,
			'id'     => $this->_actionId,
			'error'  => true,
			'data'   => array(),
		);
		$action = $this->_action.'Action';
		if (method_exists($this, $action)){
			$data = $this->$action();
			$this->_data = array_merge($this->_data, $data);
		}
	}

	protected function pageAction(){
		$filter = array(
			'p'=>$this->_actionId,
			'brands_id'=>$this->_brandsId,
			'limit'=>5,
		);
		$grid = new Crud_Grid_EventNews(null, $filter);
		echo '<pre>';
		print_r($grid->getData());
		exit('ok');
	}

	protected function cardAction(){
	}
	
	public function getData(){
		if (!$this->_data) $this->_setData();
		return $this->_data;
	}
}
