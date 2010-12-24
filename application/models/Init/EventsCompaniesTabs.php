<?php
class Init_EventsCompaniesTabs {

	public function __construct(){
	}
	
	protected function _setData(){
		$this->_data = array(
			'events' => array(
				'title'=>'Trade shows'
			),
			'companies' => array(
				'title'=>'Companies'
			),
		);

		$grid = new Crud_Grid_BrandsCategories();
		$data = $grid->getData(null, array('limit'=>'all'));
		$this->_data['events']['data'] = $data['data'];

		$grid = new Crud_Grid_CompaniesCategories();
		$data = $grid->getData(null, array('limit'=>'all'));
		$this->_data['companies']['data'] = $data['data'];
	}

	public function getData(){
		if (!$this->_data) $this->_setData();
		return $this->_data;
	}
}
