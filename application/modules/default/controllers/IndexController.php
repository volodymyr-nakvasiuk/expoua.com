<?php
class IndexController extends Abstract_Controller_FrontendController {
	
	public function indexAction(){
//		$this->view->layouts['center']['center_events_news_box'] = array('inc/center/event_news', 100);
//		$this->view->layouts['center']['center_companies_news_box'] = array('inc/center/company_news', 100);
//		$this->view->layouts['extra']['extra_companies_goods_box'] = array('inc/extra/goods_slider', 100);
		$this->view->layouts['right']['right_advert_box'] = array('inc/right/advert', 100);
        $this->view->layouts['body']['client_anons'] = array('inc/body/client_anons', 100);
        $this->view->layouts['left']['client_menu'] = array('inc/left/client_menu', 100);
//		$this->view->layouts['body']['body_events_accordion_box'] = array('inc/body/events_accordion', 100);
//		$this->view->layouts['body']['body_companies_box'] = array('inc/body/companies', 100);
	}

}