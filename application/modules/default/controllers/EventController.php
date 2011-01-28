<?php
class EventController extends Abstract_Controller_FrontendController {

	public function init(){
		parent::init();

		$this->view->activeMenu = 'events';
		$this->view->activeSubmenu = 'all';
	}
	
	public function cardAction(){
		$id = explode('-', $this->_request->getParam('id',''));
		$id = (int)$id[0];
		if ($id){
			$grid = new Crud_Grid_Event(null, array('id'=>$id, 'limit'=>1));
			$this->view->data = $grid->getData();
			if (isset($this->view->data['data'][0])){
				$this->view->data = $this->view->data['data'][0];
				$url = '/'.$this->view->lang->getLocale().'/event/card/'.$this->view->data['id'].'-'.Tools_View::getUrlAlias($this->view->data['brands_name'], true).'/';
				$tab = $this->_request->getParam('tab');
				$tab_action = $this->_request->getParam('tab_action');
				$tab_id = $this->_request->getParam('tab_id');
				if ($tab){
					$url .= $tab.'/'.$tab_action.'/'.($tab_id?$tab_id:TAB_DEFAULT_ID).'/';
				}
				if ($url!=$this->view->requestUrl) $this->_redirect($url);
				if ($this->view->data['countries_id']==52){
					$this->view->activeSubmenu = 'ukr';
				}
				/*
				$tabs = array(
					'description'=>$this->lang->translate('Description'),
					'news'=>$this->lang->translate('News'),
					//'video'=>$this->lang->translate('Video'),
					'photo'=>$this->lang->translate('Photo'),
					//'hotels'=>$this->lang->translate('Hotels'),
					'files'=>$this->lang->translate('Files'),
					//'map'=>$this->lang->translate('Map'),
					'messages'=>$this->lang->translate('Get additional information'),
				);
				*/

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

				$tabObject = new Init_Event_Files($this->view->data['brands_name_id'],$this->view->data['id'], $tab, $tab_action, $tab_id);
				$tabObject->tab_title = $this->view->lang->translate('Files');
				$this->view->tabsData[Init_Event_Files::$tab_name] = $tabObject->getData();

				$tabObject = new Init_Event_Messages($this->view->data['brands_name_id'],$this->view->data['id'], $tab, $tab_action, $tab_id);
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
		$this->view->layouts['top']['filter'] = array('inc/filter/events_news', 100);

		$services = new Tools_EventNews($this->view->requestUrl, $this->view->lang->getLocale(), $this->_request->getParam('p'));
		$filterData = $services->getFilterData();
		if ($filterData['url']!=$this->view->requestUrl) $this->_redirect($filterData['url']);

		$this->view->jsParams['filter'] = $filterData['params'];
		$this->view->jsParams['filterParams'] = Tools_EventNews::$filterParams;

		$grid = new Crud_Grid_EventNews(null, $filterData['filter']);
		$this->view->data = $grid->getData();
	}

	public function newsCardAction(){
		$this->view->activeSubmenu = 'news';
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

}
