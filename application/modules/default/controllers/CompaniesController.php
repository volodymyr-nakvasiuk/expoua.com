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
		$this->view->jsParams['filterParams'] = Tools_Companies::$filterParams;
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

				$tabObject = new Init_Companies_Services($this->view->data['id'], $tab, $tab_action, $tab_id);
				$tabObject->tab_title = $this->view->lang->translate('Products and services');
				$this->view->tabsData[Init_Companies_Services::$tab_name] = $tabObject->getData();

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
		$this->view->layoutsData['right'] = array();
		$this->view->layouts['right'] = array();

		$online = new Tools_Online($this->view->requestUrl, $this->view->lang->getLocale());
		$filterData = $online->getFilterData();

		if ($filterData['url']!=$this->view->requestUrl) $this->_redirect($filterData['url']);

		$this->view->jsParams['filter'] = $filterData['params'];
		$this->view->jsParams['filterParams'] = Tools_Online::$filterParams;

		$init = new Init_OnlineExpo($filterData['filter']);
		$this->view->data = $init->getData();

		$lang = $this->view->lang->getLocale();
		$paramName = 'category';
		$cache = Zend_Registry::get(Tools_Events::$filterParams[$paramName].Tools_Events::$cacheSuffix['id']);
		$content = array();
		foreach($cache as $id=>$category){
			$content[$category['alias']] = array(
				'url'=>HOST_NAME.'/'.$lang.'/companies/online/'.$paramName.'-'.$category['alias'].'/',
				'title'=>$category['name_'.$lang],
				'selected'=>($id==$filterData['filter']['category']),
			);
		}
		$this->view->dropdownMenu = array(
			'title'=>$this->view->translate('Select online trade show'),
			'content'=>$content,
		);
		$this->view->dataTitle = $cache[$filterData['filter']['category']]['name_'.$lang];

		$statistics = new Init_Statistics();
		$this->view->statistics['company_count'] = $statistics->getCompaniesCount();

		foreach($this->view->menuLinks['online']['submenu'] as &$submenu){
			$submenu['url'] .= $paramName.'-'.$cache[$filterData['filter']['category']]['alias'].'/';
		}
	}

	public function servicesAction(){
		$this->view->activeSubmenu = 'services';
		$this->view->layouts['top']['filter'] = array('inc/filter/companies_services', 100);

		$services = new Tools_CompanyServices($this->view->requestUrl, $this->view->lang->getLocale(), $this->_request->getParam('p'));
		$filterData = $services->getFilterData();
		if ($filterData['url']!=$this->view->requestUrl) $this->_redirect($filterData['url']);

		$this->view->jsParams['filter'] = $filterData['params'];
		$this->view->jsParams['filterParams'] = Tools_CompanyServices::$filterParams;

		$grid = new Crud_Grid_CompanyServices(null, $filterData['filter']);
		$this->view->data = $grid->getData();
	}

	public function newsAction(){
		$this->view->activeSubmenu = 'news';
		$this->view->layouts['top']['filter'] = array('inc/filter/companies_news', 100);

		$services = new Tools_CompanyNews($this->view->requestUrl, $this->view->lang->getLocale(), $this->_request->getParam('p'));
		$filterData = $services->getFilterData();
		if ($filterData['url']!=$this->view->requestUrl) $this->_redirect($filterData['url']);

		$this->view->jsParams['filter'] = $filterData['params'];
		$this->view->jsParams['filterParams'] = Tools_CompanyNews::$filterParams;

		$grid = new Crud_Grid_CompanyNews(null, $filterData['filter']);
		$this->view->data = $grid->getData();
	}
}
