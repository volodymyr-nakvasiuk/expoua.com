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
				if ($url!=$this->view->requestUrl) $this->_redirect($url);
				if ($this->view->data['countries_id']==52){
					$this->view->activeSubmenu = 'ukr';
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

	public function newsAction(){
	}

	public function newsDetailsAction(){
	}

	public function venuesAction(){
	}

}