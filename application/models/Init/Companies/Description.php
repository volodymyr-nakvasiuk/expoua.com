<?php
class Init_Companies_Description extends Init_CompaniesTab {

	static $tab_name = 'description';
	protected $_content = false;

	public function __construct($company_id, $tab, $tab_action, $tab_id, $content=false){
		parent::__construct($company_id, $tab, $tab_action, $tab_id);
		$this->_content = $content;
	}
	
	protected function pageAction(){
		return array('data'=>array('content'=>$this->_content));
	}
}
