<?php
class Init_Services_Gallery extends Init_ServicesTab {

	static $tab_name = 'photo';

	protected function pageAction(){
		$filter = array(
			'company_id'=>$this->_companyId,
			'limit'=>'all',
		);
		$grid = new Crud_Grid_ServiceGallery(null, $filter);
		return $grid->getData();
	}
}