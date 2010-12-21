<?php
class CompaniesController extends Abstract_Controller_FrontendController {

	public function init(){
		parent::init();

		$this->view->activeMenu = 'online';
		$this->view->activeSubmenu = 'categories';
	}
	
	public function indexAction(){
		$this->view->activeSubmenu = 'categories';
		$grid = new Crud_Grid_CompaniesCategories();
		$this->view->data = $grid->getData(null, array('limit'=>'all'));
		$this->view->data = $this->view->data['data'];
		$this->view->allCount = 0;
	}

	public function searchAction(){
		$this->view->activeSubmenu = 'search';
		$this->view->layouts['top']['filter'] = array('inc/filter/companies', 100);
		
		$companies = new Tools_Companies($this->view->requestUrl, $this->view->lang->getLocale(), $this->_request->getParam('p'));
		$filterData = $companies->getFilterData();
		if ($filterData['url']!=$this->view->requestUrl) $this->_redirect($filterData['url']);

		$this->view->jsParams['filter'] = $filterData['params'];
		$this->view->jsParams['filterParams'] = Tools_Events::$filterParams;
		$grid = new Crud_Grid_Companies(null, $filterData['filter']);
		$this->view->data = $grid->getData();
	}

	public function cardAction(){
		$this->view->activeSubmenu = 'search';
		$id = explode('-', $this->_request->getParam('id',''));
		$id = (int)$id[0];
		if ($id){
			$grid = new Crud_Grid_Company(null, array('id'=>$id, 'limit'=>1));
			$this->view->data = $grid->getData();
			if (isset($this->view->data['data'][0])){
				$this->view->data = $this->view->data['data'][0];
				$url = '/'.$this->view->lang->getLocale().'/companies/card/'.$this->view->data['id'].'-'.Tools_View::getUrlAlias($this->view->data['name'], true).'/';
				$tab = $this->_request->getParam('tab');
				$tab_action = $this->_request->getParam('tab_action');
				$tab_id = $this->_request->getParam('tab_id');
				if ($tab){
					$url .= $tab.'/'.$tab_action.'/'.($tab_id?$tab_id:TAB_DEFAULT_ID).'/';
				}
				if ($url!=$this->view->requestUrl) $this->_redirect($url);

				$this->view->tabsData = array();

				$tabObject = new Init_Companies_Description($this->view->data['id'], $tab, $tab_action, $tab_id, $this->view->data['description']);
				$tabObject->tab_title = $this->view->lang->translate('Description');
				$this->view->tabsData[Init_Companies_Description::$tab_name] = $tabObject->getData();

				$tabObject = new Init_Companies_News($this->view->data['id'], $tab, $tab_action, $tab_id);
				$tabObject->tab_title = $this->view->lang->translate('News');
				$this->view->tabsData[Init_Companies_News::$tab_name] = $tabObject->getData();

				/*
					$tabObject = new Init_Event_Gallery($this->view->data['brands_name_id'],$this->view->data['id'], $tab, $tab_action, $tab_id);
					$tabObject->tab_title = $this->view->lang->translate('Photo');
					$this->view->tabsData[Init_Event_Gallery::$tab_name] = $tabObject->getData();

					   $tabObject = new Init_Event_Files($this->view->data['brands_name_id'],$this->view->data['id'], $tab, $tab_action, $tab_id);
					   $tabObject->tab_title = $this->view->lang->translate('Files');
					   $this->view->tabsData[Init_Event_Files::$tab_name] = $tabObject->getData();

					   $tabObject = new Init_Event_Messages($this->view->data['brands_name_id'],$this->view->data['id'], $tab, $tab_action, $tab_id);
					   $tabObject->tab_title = $this->view->lang->translate('Get additional information');
					   $this->view->tabsData[Init_Event_Messages::$tab_name] = $tabObject->getData();
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

	public function onlineAction(){
		$this->view->activeSubmenu = 'online';
	}
}
