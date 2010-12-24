<?php
class EventsController extends Abstract_Controller_FrontendController {

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

		$this->view->jsParams['filter'] = $filterData['params'];
		$this->view->jsParams['filterParams'] = Tools_Events::$filterParams;
		$grid = new Crud_Grid_Events(null, $filterData['filter']);
		$this->view->data = $grid->getData();

		if ($filterData['params']['country']==COUNTRY_SEARCH){
			$this->view->activeSubmenu = COUNTRY_ABBR;
		}
	}

}