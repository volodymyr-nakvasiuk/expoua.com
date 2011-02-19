<?php
class Init_VenuesTab {

	static $tab_name  = 'tab';
	public $tab_title = false;
	protected $_data     = false;
	protected $_venueId  = false;
	protected $_isActive = false;
	protected $_action   = false;
	protected $_actionId = false;

	public function __construct($venue_id, $tab, $tab_action, $tab_id){
		$this->_venueId  = $venue_id;
		$tabClass = get_class($this);
		$this->_isActive = ($tabClass::$tab_name==$tab);
		$this->_action   = $this->_isActive?$tab_action:TAB_DEFAULT_ACTION;
		$this->_actionId = $this->_isActive?$tab_id:TAB_DEFAULT_ID;
	}

	protected function _setData(){
		$this->_data = array(
			'venue_id'  => $this->_venueId,
			'title'     => $this->tab_title,
			'active'    => $this->_isActive,
			'action'    => $this->_action,
			'id'        => $this->_actionId,
			'error'     => true,
			'data'      => array(),
		);
		$action = $this->_action.'Action';
		if (method_exists($this, $action)){
			$this->_data['error'] = false;
			$data = $this->$action();
			$this->_data = array_merge($this->_data, $data);
		}
	}

	public function getData(){
		if (!$this->_data) $this->_setData();
		return $this->_data;
	}
}
