<?php
class EventsController extends Abstract_Controller_FrontendController {

	public $moduleName = 'Exhibition';

	public function init(){
		parent::init();
		$this->view->activeMenu = 'events';
		$this->view->activeSubmenu = 'all';
	}
	
	public function searchAction(){
		$this->view->layouts['top']['filter'] = array('inc/filter/events', 100);
		$events = new Tools_Events($this->view->requestUrl, DEFAULT_LANG_CODE, $this->_request->getParam('p'));
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

		$this->view->layouts['center']['center_services'] = array('inc/center/services', 100);
		$this->view->layouts['center']['center_events_by_countries'] = array('inc/center/events_by_countries', 100);
		$this->view->layouts['center']['center_events_by_categories'] = array('inc/center/events_by_categories', 100);

		if ($filterData['params']['country']==COUNTRY_SEARCH){
			$this->view->activeSubmenu = COUNTRY_ABBR;
		}
	}

	public function cardAction(){
		$this->moduleName = 'Event';
		$id = explode('-', $this->_request->getParam('id',''));
		$id = (int)$id[0];
		if ($id){
			$grid = new Crud_Grid_Event(null, array('id'=>$id, 'limit'=>1));
			$this->view->data = $grid->getData();
			if (isset($this->view->data['data'][0])){
				$this->view->data = $this->view->data['data'][0];
				
				$countries = Zend_Registry::get("countries".Tools_Events::$cacheSuffix['id']);
				$cities = Zend_Registry::get("cities".Tools_Events::$cacheSuffix['id']);

				$url = '/'.DEFAULT_LANG_CODE.'/events/card/'
						.$countries[$this->view->data['regions_id']][$this->view->data['countries_id']]['alias'].'/'
						.$cities[$this->view->data['regions_id']][$this->view->data['countries_id']][$this->view->data['cities_id']]['alias'].'/'
						.$this->view->data['id'];

				$tab = $this->_request->getParam('tab');
				$tab_action = $this->_request->getParam('tab_action');
				$tab_id = $this->_request->getParam('tab_id');
				if ($tab){
					$url .= '/'.$tab.'/'.$tab_action.'/'.($tab_id?$tab_id:TAB_DEFAULT_ID).'/';
				}
				else {
					$url .= '-'.Tools_View::getUrlAlias($this->view->data['brands_name'], true).'/';
				}
				if ($url!=$this->view->requestUrl) $this->_redirect($url);

				$tab_id = explode('-', $tab_id);
				$tab_id = (int)$tab_id[0];

				if ($this->view->data['countries_id']==COUNTRY_ID){
					$this->view->activeSubmenu = COUNTRY_ABBR;
				}

				$this->view->tabsData = array();

				$tabObject = new Init_Event_Description($this->view->data['brands_name_id'],$this->view->data['id'], $tab, $tab_action, $tab_id, $this->view->data['description']);
				$tabObject->tab_title = $this->view->lang->translate('Description');
				$this->view->tabsData[Init_Event_Description::$tab_name] = $tabObject->getData();

				$tabObject = new Init_Event_News($this->view->data['brands_name_id'],$this->view->data['id'], $tab, $tab_action, $tab_id);
				$tabObject->tab_title = $this->view->lang->translate('News');
				$this->view->tabsData[Init_Event_News::$tab_name] = $tabObject->getData();

				$tabObject = new Init_Event_Gallery($this->view->data['brands_name_id'],$this->view->data['id'], $tab, $tab_action, $tab_id);
				$tabObject->tab_title = $this->view->lang->translate('Photo');
				$this->view->tabsData[Init_Event_Gallery::$tab_name] = $tabObject->getData();

				$tabObject = new Init_Event_Map($this->view->data['brands_name_id'],$this->view->data['id'], $tab, $tab_action, $tab_id, $this->view->data);
				$tabObject->tab_title = $this->view->lang->translate('Map');
				$this->view->tabsData[Init_Event_Map::$tab_name] = $tabObject->getData();
				if ($this->view->tabsData[Init_Event_Map::$tab_name]['data']){
					$this->view->headScript()->appendFile('http://maps.google.com/maps/api/js?sensor=false', 'text/javascript', array('non-cache'=>true));
				}

				$tabObject = new Init_Event_Files($this->view->data['brands_name_id'],$this->view->data['id'], $tab, $tab_action, $tab_id);
				$tabObject->tab_title = $this->view->lang->translate('Files');
				$this->view->tabsData[Init_Event_Files::$tab_name] = $tabObject->getData();

				$tabObject = new Init_Event_Video($this->view->data['brands_name_id'],$this->view->data['id'], $tab, $tab_action, $tab_id);
				$tabObject->tab_title = $this->view->lang->translate('Video');
				$this->view->tabsData[Init_Event_Video::$tab_name] = $tabObject->getData();

				$tabObject = new Init_Event_Messages($this->view->data['brands_name_id'],$this->view->data['id'], $tab, $tab_action, $tab_id, $this->view->data);
				$tabObject->tab_title = $this->view->lang->translate('Get additional information');
				$this->view->tabsData[Init_Event_Messages::$tab_name] = $tabObject->getData();
			}
			else {
				$this->_forward('error', 'error');
				return;
			}
		}
		else {
			$this->_forward('error', 'error');
			return;
		}
	}

	public function newsAction(){
		$this->view->activeSubmenu = 'news';
		$this->moduleName = 'News';
		$this->view->layouts['top']['filter'] = array('inc/filter/events_news', 100);

		$services = new Tools_EventNews($this->view->requestUrl, DEFAULT_LANG_CODE, $this->_request->getParam('p'));
		$filterData = $services->getFilterData();
		if ($filterData['url']!=$this->view->requestUrl) $this->_redirect($filterData['url']);

		if (isset($filterData['filter']['category'])){
			$this->brandsCategoryId = $filterData['filter']['category'];
		}

		$this->view->jsParams['filter'] = $filterData['params'];
		$this->view->jsParams['filterParams'] = Tools_EventNews::$filterParams;

		$grid = new Crud_Grid_EventNews(null, $filterData['filter']);
		$this->view->data = $grid->getData();

		$init = new Init_Services(array('Hotels','Stands','Translators','Polygraphy'));
		$this->view->layoutsData['center']['center_banners_recommends_box'] = $init->getData();
		$this->view->layouts['center']['center_banners_recommends_box'] = array('inc/banners/recommends', 100);

		$this->view->layouts['center']['center_events_by_countries'] = array('inc/center/events_by_countries', 100);
		$this->view->layouts['center']['center_events_by_categories'] = array('inc/center/events_by_categories', 100);
	}

	public function newsCardAction(){
		$this->view->activeSubmenu = 'news';
		$this->moduleName = 'News';
		$id = explode('-', $this->_request->getParam('id',''));
		$id = (int)$id[0];
		if ($id){
			$grid = new Crud_Grid_EventOneNews(null, array('id'=>$id, 'limit'=>1));
			$this->view->data = $grid->getData();
			if (isset($this->view->data['data'][0])){
				$this->view->data = $this->view->data['data'][0];
			}
			else {
				$this->_forward('error', 'error');
				return;
			}
		}
		else {
			$this->_forward('error', 'error');
			return;
		}
	}

	public function organizerAction(){
		$id = explode('-', $this->_request->getParam('id',''));
		$id = (int)$id[0];
		if ($id){
			$grid = new Crud_Grid_Organizer(null, array('id'=>$id, 'limit'=>1));
			$this->view->data = $grid->getData();
			if (isset($this->view->data['data'][0])){
				$this->view->data = $this->view->data['data'][0];
				$url = '/'.DEFAULT_LANG_CODE.'/events/organizer/'.$this->view->data['id'];
				$tab = $this->_request->getParam('tab');
				$tab_action = $this->_request->getParam('tab_action');
				$tab_id = $this->_request->getParam('tab_id');
				if ($tab){
					$url .= '/'.$tab.'/'.$tab_action.'/'.($tab_id?$tab_id:TAB_DEFAULT_ID).'/';
				}
				else {
					$url .= '-'.Tools_View::getUrlAlias($this->view->data['name'], true).'/';
				}
				if ($url!=$this->view->requestUrl) $this->_redirect($url);

				$tab_id = explode('-', $tab_id);
				$tab_id = (int)$tab_id[0];

				if ($this->view->data['countries_id']==COUNTRY_ID){
					$this->view->activeSubmenu = COUNTRY_ABBR;
				}

				$this->view->tabsData = array();

				$tabObject = new Init_Event_Description(0,$this->view->data['id'], $tab, $tab_action, $tab_id, $this->view->data['description']);
				$tabObject->tab_title = $this->view->lang->translate('Description');
				$this->view->tabsData[Init_Event_Description::$tab_name] = $tabObject->getData();

				$tabObject = new Init_Event_Messages(0,$this->view->data['id'], $tab, $tab_action, $tab_id, $this->view->data);
				$tabObject->tab_title = $this->view->lang->translate('Get additional information');
				$this->view->tabsData[Init_Event_Messages::$tab_name] = $tabObject->getData();
			}
			else {
				$this->_forward('error', 'error');
				return;
			}
		}
		else {
			$this->_forward('error', 'error');
			return;
		}
	}
}