<?php
class Init_Companies_Services extends Init_CompaniesTab {

	static $tab_name = 'services';

	protected function pageAction(){
		$filter = array(
			'p'=>$this->_actionId,
			'companies_id'=>$this->_companyId,
			'limit'=>5,
		);
		$grid = new Crud_Grid_CompanyServices(null, $filter);
		return $grid->getData();
	}

	protected function cardAction(){
		$grid = new Crud_Grid_CompanyOneService(null, array('id'=>$this->_actionId, 'limit'=>1));
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
