<?php
class CompaniesController extends Abstract_Controller_FrontendController {
	
	public function indexAction(){
		$grid = new Crud_Grid_CompaniesCategories();
		$this->view->data = $grid->getData(null, array('limit'=>'all'));
		$this->view->data = $this->view->data['data'];
		$this->view->allCount = 0;
	}
}