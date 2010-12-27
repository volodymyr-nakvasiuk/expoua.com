<?php
class IndexController extends Abstract_Controller_FrontendController {
	
	public $moduleName = 'Mainpage';

	public function indexAction(){
		/* ---------------------- Start Anton Templates ---------------------- */
		//   //$this->view->layouts['body']['client_reg'] = array('inc/body/register', 100);
		//$this->view->layouts['right']['right_advert_box'] = array('inc/right/advert', 100);
		//   //$this->view->layouts['body']['companies_search_box'] = array('inc/body/companies_search', 100);
		//   //$this->view->layouts['center']['company_news'] = array('inc/center/top_companies', 100);
		//$this->view->layouts['body']['user_add_about_company'] = array('inc/body/user_add_about_company', 100);
		//$this->view->layouts['left']['client_menu'] = array('inc/left/client_menu', 100);
		/* ----------------------- End Anton Templates ----------------------- */

		$this->view->layouts['top']['filter'] = array('inc/filter/index', 100);

		//$grid = new Crud_Grid_Events(null, array('country'=>52,'limit'=>3));
		$init = new Init_Prlines($this->moduleName, $this->brandsCategoryId);
		$this->view->layoutsData['body']['body_events_accordion_box'] = $init->getData();
		$this->view->layouts['body']['body_events_accordion_box'] = array('inc/body/events_accordion', 100);

		$grid = new Crud_Grid_Companies(null, array('country'=>52,'logo'=>1,'limit'=>8));
		$this->view->layoutsData['body']['body_companies_box'] = $grid->getData();
		$this->view->layouts['body']['body_companies_box'] = array('inc/body/companies', 100);

		$grid = new Crud_Grid_EventNews(null, array('limit'=>6));
		$this->view->layoutsData['center']['center_events_news_box'] = $grid->getData();
		$this->view->layouts['center']['center_events_news_box'] = array('inc/center/event_news', 100);

		$grid = new Crud_Grid_CompanyNews(null, array('limit'=>8));
		$this->view->layoutsData['center']['center_companies_news_box'] = $grid->getData();
		$this->view->layouts['center']['center_companies_news_box'] = array('inc/center/company_news', 100);

		$grid = new Crud_Grid_CompanyOneService(null, array('photo'=>1, 'limit'=>36));
		$grid->currentSelect->group('companies_id');
		$this->view->layoutsData['extra']['extra_companies_goods_box'] = $grid->getData();
		$this->view->layouts['extra']['extra_companies_goods_box'] = array('inc/extra/goods_slider', 100);

		$init = new Init_EventsCompaniesTabs();
		$this->view->layoutsData['extra']['extra_events_companies_tabs_box'] = $init->getData();
		$this->view->layouts['extra']['extra_events_companies_tabs_box'] = array('inc/extra/events_companies_tabs', 100);

		$this->view->layouts['right']['right_banner_box'] = array('inc/right/banner', 100);

		$tool = new Tools_Banner(null, $this->moduleName, $this->brandsCategoryId);
		$this->view->layoutsData['right']['right_advert_box'] = $tool->getData();
		$tool->updateStat();
		$this->view->layouts['right']['right_advert_box'] = array('inc/right/advert', 100);

		$grid = new Crud_Grid_Companies(null, array('country'=>52,'limit'=>20));
		$this->view->layoutsData['right']['right_companies_box'] = $grid->getData();
		$this->view->layouts['right']['right_companies_box'] = array('inc/right/top_companies', 100);
	}
}
