<?php
class VenuesController extends Abstract_Controller_FrontendController {

	public function init(){
		parent::init();
		$this->view->activeMenu = 'events';
		$this->view->activeSubmenu = 'venues';
	}
	
	public function searchAction(){
		$this->view->layouts['top']['filter'] = array('inc/filter/venues', 100);
		$venues = new Tools_Venues($this->view->requestUrl, DEFAULT_LANG_CODE, $this->_request->getParam('p'));
		$filterData = $venues->getFilterData();
		if ($filterData['url']!=$this->view->requestUrl) $this->_redirect($filterData['url']);

		$this->view->jsParams['filter'] = $filterData['params'];
		$this->view->jsParams['filterParams'] = Tools_Venues::$filterParams;
		$grid = new Crud_Grid_Venues(null, $filterData['filter']);
		$this->view->data = $grid->getData();

		$init = new Init_Services(array('Hotels','Stands','Translators','Polygraphy'));
		$this->view->layoutsData['center']['center_banners_recommends_box'] = $init->getData();
		$this->view->layouts['center']['center_banners_recommends_box'] = array('inc/banners/recommends', 100);

		$grid = new Crud_Grid_EventNews(null, array('limit'=>5));
		$this->view->layoutsData['center']['center_events_news_box'] = $grid->getData();
		$this->view->layouts['center']['center_events_news_box'] = array('inc/center/event_news', 100);

		$this->view->layouts['center']['center_events_by_countries'] = array('inc/center/events_by_countries', 100);
		$this->view->layouts['center']['center_events_by_categories'] = array('inc/center/events_by_categories', 100);
	}

	public function cardAction(){
		$id = explode('-', $this->_request->getParam('id',''));
		$id = (int)$id[0];
		if ($id){
			$grid = new Crud_Grid_Venue(null, array('id'=>$id, 'limit'=>1));
			$this->view->data = $grid->getData();
			if (isset($this->view->data['data'][0])){
				$this->view->data = $this->view->data['data'][0];
				$url = '/'.DEFAULT_LANG_CODE.'/venues/card/'.$this->view->data['id'].'-'.Tools_View::getUrlAlias($this->view->data['name'], true).'/';
				$tab = $this->_request->getParam('tab');
				$tab_action = $this->_request->getParam('tab_action');
				$tab_id = $this->_request->getParam('tab_id');
				if ($tab){
					$url .= $tab.'/'.$tab_action.'/'.($tab_id?$tab_id:TAB_DEFAULT_ID).'/';
				}
				if ($url!=$this->view->requestUrl) $this->_redirect($url);

				$tab_id = explode('-', $tab_id);
				$tab_id = (int)$tab_id[0];

				$this->view->tabsData = array();

				$tabObject = new Init_Venues_Description($this->view->data['id'], $tab, $tab_action, $tab_id, $this->view->data['description']);
				$tabObject->tab_title = $this->view->lang->translate('Description');
				$this->view->tabsData[Init_Venues_Description::$tab_name] = $tabObject->getData();

				$tabObject = new Init_Venues_Gallery($this->view->data['id'], $tab, $tab_action, $tab_id, array($this->view->data['image_map'],$this->view->data['image_plan'],$this->view->data['image_view']));
				$tabObject->tab_title = $this->view->lang->translate('Scheme & Photo');
				$this->view->tabsData[Init_Venues_Gallery::$tab_name] = $tabObject->getData();

				$tabObject = new Init_Venues_Events($this->view->data['id'], $tab, $tab_action, $tab_id);
				$tabObject->tab_title = $this->view->lang->translate('Trade shows');
				$this->view->tabsData[Init_Venues_Events::$tab_name] = $tabObject->getData();

				$tabObject = new Init_Venues_Map($this->view->data['id'], $tab, $tab_action, $tab_id, $this->view->data);
				$tabObject->tab_title = $this->view->lang->translate('Map');
				$this->view->tabsData[Init_Venues_Map::$tab_name] = $tabObject->getData();
				if ($this->view->tabsData[Init_Venues_Map::$tab_name]['data']){
					$this->view->headScript()->appendFile('http://maps.google.com/maps/api/js?sensor=false', 'text/javascript', array('non-cache'=>true));
				}

				$tabObject = new Init_Venues_Messages($this->view->data['id'], $tab, $tab_action, $tab_id, $this->view->data);
				$tabObject->tab_title = $this->view->lang->translate('Get additional information');
				$this->view->tabsData[Init_Venues_Messages::$tab_name] = $tabObject->getData();
/*
				$tabObject = new Init_Event_Files($this->view->data['brands_name_id'],$this->view->data['id'], $tab, $tab_action, $tab_id);
				$tabObject->tab_title = $this->view->lang->translate('Files');
				$this->view->tabsData[Init_Event_Files::$tab_name] = $tabObject->getData();
*/
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