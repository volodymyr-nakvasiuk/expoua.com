<?php
class ServicesController extends Abstract_Controller_FrontendController {

	public function init(){
		parent::init();

		$this->view->activeMenu = 'events';
		$this->view->activeSubmenu = 'services';
	}
	
	public function indexAction(){
		$grid = new Crud_Grid_ServicesCategories();
		$this->view->data = $grid->getData(null, array('limit'=>'all'));
		$this->view->data = $this->view->data['data'];
		$this->view->allCount = 0;

		$this->view->layouts['top']['filter'] = array('inc/filter/services_by_categories', 100);
		$this->view->jsParams['filter'] = array();
		$this->view->jsParams['filterParams'] = array('category'=>Tools_Companies::$filterParams['category']);
	}

	public function searchAction(){
		$this->view->layouts['top']['filter'] = array('inc/filter/services_by_categories', 100);

		$companies = new Tools_Services($this->view->requestUrl, DEFAULT_LANG_CODE, $this->_request->getParam('p'));
		$filterData = $companies->getFilterData();
		if ($filterData['url']!=$this->view->requestUrl) $this->_redirect($filterData['url']);

		$this->view->jsParams['filter'] = $filterData['params'];
		$this->view->jsParams['filterParams'] = Tools_Companies::$filterParams;

		$filterData['filter']['country'] = COUNTRY_ID;
		$grid = new Crud_Grid_Services(null, $filterData['filter']);
		$this->view->data = $grid->getData();

		$paramName = 'category';
		$cache = Zend_Registry::get(Tools_Events::$filterParams[$paramName].Tools_Events::$cacheSuffix['id']);
		$this->view->dataTitle = $cache[$filterData['filter']['category']]['name'];
	}

	public function cardAction(){
		$id = explode('-', $this->_request->getParam('id',''));
		$id = (int)$id[0];
		if ($id){
			$grid = new Crud_Grid_Service(null, array('id'=>$id, 'limit'=>1));
			$this->view->data = $grid->getData();
			if (isset($this->view->data['data'][0])){
				$this->view->data = $this->view->data['data'][0];
				$url = '/'.DEFAULT_LANG_CODE.'/services/card/'.$this->view->data['id'].'-'.Tools_View::getUrlAlias($this->view->data['name'], true).'/';
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

				$tabObject = new Init_Services_Description($this->view->data['id'], $tab, $tab_action, $tab_id, $this->view->data['description']);
				$tabObject->tab_title = $this->view->lang->translate('Description');
				$this->view->tabsData[Init_Services_Description::$tab_name] = $tabObject->getData();

				$tabObject = new Init_Services_Gallery($this->view->data['id'], $tab, $tab_action, $tab_id);
				$tabObject->tab_title = $this->view->lang->translate('Photo');
				$this->view->tabsData[Init_Services_Gallery::$tab_name] = $tabObject->getData();

				$tabObject = new Init_Services_Messages($this->view->data['id'], $tab, $tab_action, $tab_id, $this->view->data);
				$tabObject->tab_title = $this->view->lang->translate('Get additional information');
				$this->view->tabsData[Init_Services_Messages::$tab_name] = $tabObject->getData();
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
