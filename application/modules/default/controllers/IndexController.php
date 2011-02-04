<?php
class IndexController extends Abstract_Controller_FrontendController {
	
	public $moduleName = 'Mainpage';

	public function indexAction(){
		/* ---------------------- Start Anton Templates ---------------------- */
		//   //$this->view->layouts['body']['client_reg'] = array('inc/body/register', 100);
		//   //$this->view->layouts['body']['companies_search_box'] = array('inc/body/companies_search', 100);
		//   //$this->view->layouts['center']['company_news'] = array('inc/center/top_companies', 100);
		//$this->view->layouts['body']['user_add_about_company'] = array('inc/body/user_add_about_company', 100);
		//$this->view->layouts['left']['client_menu'] = array('inc/left/client_menu', 100);
		/* ----------------------- End Anton Templates ----------------------- */

		$this->view->layouts['top']['filter'] = array('inc/filter/index', 100);

		$this->view->layouts['body']['body_events_title_box'] = array('inc/body/events_title', 100);

		$tool = new Tools_Banner(array('top_wide_flash_1', 'top_wide_flash_2'), $this->moduleName, true, $this->brandsCategoryId, 2);
		$this->view->banners['250x120']['body_banners_250x120_box1'] = array($tool->getData(), 100);
		$tool->updateStat();
		$this->view->layouts['body']['body_banners_250x120_box1'] = array('inc/banners/banner250x120', 100);

		$init = new Init_Prlines($this->moduleName, $this->brandsCategoryId);
		$this->view->layoutsData['body']['body_events_prlines_box'] = $init->getData();
		$this->view->layouts['body']['body_events_prlines_box'] = array('inc/body/events_prlines', 100);

		$grid = new Crud_Grid_Companies(null, array('country'=>52,'logo'=>1,'limit'=>8));
		$this->view->layoutsData['body']['body_companies_box'] = $grid->getData();
		$this->view->layouts['body']['body_companies_box'] = array('inc/body/companies', 100);

		$tool = new Tools_Banner(array('mid_wide_flash1', 'mid_wide_flash2'), $this->moduleName, true, $this->brandsCategoryId, 2);
		$this->view->banners['250x120']['body_banners_250x120_box2'] = array($tool->getData(), 100);
		$tool->updateStat();
		$this->view->layouts['body']['body_banners_250x120_box2'] = array('inc/banners/banner250x120', 100);

		$grid = new Crud_Grid_CompanyOneService(null, array('photo'=>1, 'limit'=>32));
		$grid->currentSelect->group('companies_id');
		$this->view->layoutsData['body']['body_companies_goods_box'] = $grid->getData();
		$this->view->layouts['body']['body_companies_goods_box'] = array('inc/body/goods_slider', 100);

		$init = new Init_Services(array('Hotels','Stands','Translators','Polygraphy'));
		$this->view->layoutsData['center']['center_banners_recommends_box'] = $init->getData();
		$this->view->layouts['center']['center_banners_recommends_box'] = array('inc/banners/recommends', 100);

		$grid = new Crud_Grid_EventNews(null, array('limit'=>5));
		$this->view->layoutsData['center']['center_events_news_box'] = $grid->getData();
		$this->view->layouts['center']['center_events_news_box'] = array('inc/center/event_news', 100);

		$grid = new Crud_Grid_CompanyNews(null, array('limit'=>5));
		$this->view->layoutsData['center']['center_companies_news_box'] = $grid->getData();
		$this->view->layouts['center']['center_companies_news_box'] = array('inc/center/company_news', 100);

		$init = new Init_EventsCompaniesTabs();
		$this->view->layoutsData['extra']['extra_events_companies_tabs_box'] = $init->getData();
		$this->view->layouts['extra']['extra_events_companies_tabs_box'] = array('inc/extra/events_companies_tabs', 100);

		$grid = new Crud_Grid_Companies(null, array('country'=>52,'limit'=>20));
		$this->view->layoutsData['right']['right_companies_box'] = $grid->getData();
		$this->view->layouts['right']['right_companies_box'] = array('inc/right/top_companies', 100);
	}
}
