<?php
class CompaniesController extends Abstract_Controller_FrontendController {

	public $moduleName = 'Companies';

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

		$this->view->menuLinks[$this->view->activeMenu]['submenu'] = array();

		$this->view->layouts['top']['filter'] = array('inc/filter/companies_by_categories', 100);
		$this->view->jsParams['filter'] = array();
		$this->view->jsParams['filterParams'] = array('category'=>Tools_Companies::$filterParams['category']);

		$statistics = new Init_Statistics();
		$this->view->statistics['company_count'] = $statistics->getCompaniesCount();
		$this->view->layouts['center']['center_companies_title_box'] = array('inc/center/companies_title', 100);

		$grid = new Crud_Grid_Companies(null, array('country'=>COUNTRY_ID,'limit'=>20));
		$this->view->layoutsData['center']['center_companies_box'] = $grid->getData();
		$this->view->layouts['center']['center_companies_box'] = array('inc/center/top_companies', 100);
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
				$url = '/'.DEFAULT_LANG_CODE.'/companies/card/'.$this->view->data['id'];
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
				$paramName = 'category';
				$cache = Zend_Registry::get(Tools_Events::$filterParams[$paramName].Tools_Events::$cacheSuffix['id']);

				foreach($this->view->menuLinks[$this->view->activeMenu]['submenu'] as &$submenu){
					$submenu['url'] .= $paramName.'-'.$cache[$this->view->data['category_id']]['alias'].'/';
				}
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

		$online = new Tools_Online($this->view->requestUrl, DEFAULT_LANG_CODE);
		$filterData = $online->getFilterData();

		if ($filterData['url']!=$this->view->requestUrl) $this->_redirect($filterData['url']);

		$this->view->jsParams['filter'] = $filterData['params'];
		$this->view->jsParams['filterParams'] = Tools_Online::$filterParams;

		$init = new Init_OnlineExpo($filterData['filter']);
		$this->view->data = $init->getData();

		$this->view->layouts['top']['filter'] = array('inc/filter/companies_by_categories', 100);
		$this->view->jsParams['filter'] = $filterData['params'];
		$this->view->jsParams['filterParams'] = array('category'=>Tools_Companies::$filterParams['category']);

		$statistics = new Init_Statistics();
		$this->view->statistics['company_count'] = $statistics->getCompaniesCount();

		$paramName = 'category';
		$cache = Zend_Registry::get(Tools_Events::$filterParams[$paramName].Tools_Events::$cacheSuffix['id']);
		$this->view->dataTitle = $cache[$filterData['filter']['category']]['name'];

		foreach($this->view->menuLinks[$this->view->activeMenu]['submenu'] as &$submenu){
			$submenu['url'] .= $paramName.'-'.$cache[$filterData['filter']['category']]['alias'].'/';
		}
	}

	public function searchAction(){
		$this->view->activeSubmenu = 'search';
		$this->view->layouts['top']['filter'] = array('inc/filter/companies_by_categories', 100);

		$companies = new Tools_Companies($this->view->requestUrl, DEFAULT_LANG_CODE, $this->_request->getParam('p'));
		$filterData = $companies->getFilterData();
		if ($filterData['url']!=$this->view->requestUrl) $this->_redirect($filterData['url']);

		if (isset($filterData['filter']['category'])){
			$this->brandsCategoryId = $filterData['filter']['category'];
		}

		$this->view->jsParams['filter'] = $filterData['params'];
		$this->view->jsParams['filterParams'] = Tools_Companies::$filterParams;

		$filterData['filter']['country'] = COUNTRY_ID;
		$grid = new Crud_Grid_Companies(null, $filterData['filter']);
		$this->view->data = $grid->getData();

		$statistics = new Init_Statistics();
		$this->view->statistics['company_count'] = $statistics->getCompaniesCount();
		$this->view->layouts['center']['center_companies_title_box'] = array('inc/center/companies_title', 100);

		$grid = new Crud_Grid_Companies(null, array('country'=>COUNTRY_ID,'limit'=>20));
		$this->view->layoutsData['center']['center_companies_box'] = $grid->getData();
		$this->view->layouts['center']['center_companies_box'] = array('inc/center/top_companies', 100);

		$paramName = 'category';
		$cache = Zend_Registry::get(Tools_Events::$filterParams[$paramName].Tools_Events::$cacheSuffix['id']);
		$this->view->dataTitle = $cache[$filterData['filter']['category']]['name'];

		foreach($this->view->menuLinks[$this->view->activeMenu]['submenu'] as &$submenu){
			$submenu['url'] .= $paramName.'-'.$cache[$filterData['filter']['category']]['alias'].'/';
		}
	}

	public function servicesAction(){
		$this->view->activeSubmenu = 'services';
		$this->moduleName = 'CompaniesServices';
		$this->view->layouts['top']['filter'] = array('inc/filter/companies_by_categories', 100);

		$services = new Tools_CompanyServices($this->view->requestUrl, DEFAULT_LANG_CODE, $this->_request->getParam('p'));
		$filterData = $services->getFilterData();
		if ($filterData['url']!=$this->view->requestUrl) $this->_redirect($filterData['url']);

		if (isset($filterData['filter']['category'])){
			$this->brandsCategoryId = $filterData['filter']['category'];
		}

		$this->view->jsParams['filter'] = $filterData['params'];
		$this->view->jsParams['filterParams'] = Tools_CompanyServices::$filterParams;

		$filterData['filter']['country'] = COUNTRY_ID;
		$grid = new Crud_Grid_CompanyServices(null, $filterData['filter']);
		$this->view->data = $grid->getData();

		$statistics = new Init_Statistics();
		$this->view->statistics['company_count'] = $statistics->getCompaniesCount();
		$this->view->layouts['center']['center_companies_title_box'] = array('inc/center/companies_title', 100);

		$grid = new Crud_Grid_Companies(null, array('country'=>COUNTRY_ID,'limit'=>20));
		$this->view->layoutsData['center']['center_companies_box'] = $grid->getData();
		$this->view->layouts['center']['center_companies_box'] = array('inc/center/top_companies', 100);

		$paramName = 'category';
		$cache = Zend_Registry::get(Tools_Events::$filterParams[$paramName].Tools_Events::$cacheSuffix['id']);
		$this->view->dataTitle = $cache[$filterData['filter']['category']]['name'];

		foreach($this->view->menuLinks[$this->view->activeMenu]['submenu'] as &$submenu){
			$submenu['url'] .= $paramName.'-'.$cache[$filterData['filter']['category']]['alias'].'/';
		}
	}

	public function newsAction(){
		$this->view->activeSubmenu = 'news';
		$this->moduleName = 'CompaniesNews';
		$this->view->layouts['top']['filter'] = array('inc/filter/companies_by_categories', 100);

		$services = new Tools_CompanyNews($this->view->requestUrl, DEFAULT_LANG_CODE, $this->_request->getParam('p'));
		$filterData = $services->getFilterData();
		if ($filterData['url']!=$this->view->requestUrl) $this->_redirect($filterData['url']);

		if (isset($filterData['filter']['category'])){
			$this->brandsCategoryId = $filterData['filter']['category'];
		}

		$this->view->jsParams['filter'] = $filterData['params'];
		$this->view->jsParams['filterParams'] = Tools_CompanyNews::$filterParams;

		$filterData['filter']['country'] = COUNTRY_ID;
		$grid = new Crud_Grid_CompanyNews(null, $filterData['filter']);
		$this->view->data = $grid->getData();

		$statistics = new Init_Statistics();
		$this->view->statistics['company_count'] = $statistics->getCompaniesCount();
		$this->view->layouts['center']['center_companies_title_box'] = array('inc/center/companies_title', 100);

		$grid = new Crud_Grid_Companies(null, array('country'=>COUNTRY_ID,'limit'=>20));
		$this->view->layoutsData['center']['center_companies_box'] = $grid->getData();
		$this->view->layouts['center']['center_companies_box'] = array('inc/center/top_companies', 100);

		$paramName = 'category';
		$cache = Zend_Registry::get(Tools_Events::$filterParams[$paramName].Tools_Events::$cacheSuffix['id']);
		$this->view->dataTitle = $cache[$filterData['filter']['category']]['name'];

		foreach($this->view->menuLinks[$this->view->activeMenu]['submenu'] as &$submenu){
			$submenu['url'] .= $paramName.'-'.$cache[$filterData['filter']['category']]['alias'].'/';
		}
	}
}
