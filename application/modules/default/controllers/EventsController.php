<?php
class EventsController extends Abstract_Controller_FrontendController {

	public $moduleName = 'Exhibition';

	public function init(){
		parent::init();
		$this->view->activeMenu = 'events';
		$this->view->activeSubmenu = 'all';
	}
	
	public function indexAction(){
		$this->view->layouts['top']['filter'] = array('inc/filter/events', 100);
		$events = new Tools_Events($this->view->requestUrl, $this->view->lang->getLocale(), $this->_request->getParam('p'));
		$filterData = $events->getFilterData();
		if ($filterData['url']!=$this->view->requestUrl) $this->_redirect($filterData['url']);

		if (isset($filterData['filter']['category'])){
			$this->brandsCategoryId = $filterData['filter']['category'];
		}

		$this->view->jsParams['filter'] = $filterData['params'];
		$this->view->jsParams['filterParams'] = Tools_Events::$filterParams;
		$grid = new Crud_Grid_Events(null, $filterData['filter']);
		$this->view->data = $grid->getData();

		$init = new Init_Services(array('Hotels','Stands','Translators','Polygraphy'));
		$this->view->layoutsData['center']['center_banners_recommends_box'] = $init->getData();
		$this->view->layouts['center']['center_banners_recommends_box'] = array('inc/banners/recommends', 100);

		$grid = new Crud_Grid_EventNews(null, array('limit'=>5));
		$this->view->layoutsData['center']['center_events_news_box'] = $grid->getData();
		$this->view->layouts['center']['center_events_news_box'] = array('inc/center/event_news', 100);

		$this->view->layouts['center']['center_events_by_countries'] = array('inc/center/events_by_countries', 100);
		$this->view->layouts['center']['center_events_by_categories'] = array('inc/center/events_by_categories', 100);

		$tool = new Tools_Banner('side250', $this->moduleName, true, $this->brandsCategoryId, 1);
		$this->view->banners['250x250']['right_banners_250x250_box'] = array($tool->getData(), 100);
		$tool->updateStat();
		$this->view->layouts['right']['right_banners_250x250_box'] = array('inc/banners/banner250x250', 100);

		$tool = new Tools_Banner(null, $this->moduleName, true, $this->brandsCategoryId);
		$this->view->layoutsData['right']['right_banners_advert_box'] = $tool->getData();
		$tool->updateStat();
		$this->view->layouts['right']['right_banners_advert_box'] = array('inc/banners/advert', 100);

		if ($filterData['params']['country']==COUNTRY_SEARCH){
			$this->view->activeSubmenu = COUNTRY_ABBR;
		}
	}

}