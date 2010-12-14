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
				$this->view->tabs = array();
				$news = new Init_Event_News($this->view->data['brands_name_id'], $tab, $tab_action, $tab_id);
				$this->view->tabs[Init_Event_News::$tab_name] = $news->getData();
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
