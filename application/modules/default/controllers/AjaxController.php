<?php

/**
 * CarController
 *
 * @author
 * @version
 */

class AjaxController extends Abstract_Controller_FrontendController {
	
	public function preDispatch() {}
	
	public function init(){
		if(!$this->_request->isXmlHttpRequest()){
			$this->_redirect('/error/error/');
		}
		parent::init();
		$this->_helper->layout->disableLayout();
		$this->_response->setHeader('Content-type', 'application/json');
	}
	
	public function postDispatch(){}
	
	public function getAction(){
		$data = array('success'=>false);
		$this->_helper->viewRenderer->setNoRender();
		$cacheId = $this->_request->getParam('id');
		$cacheNames = array_diff(array_values(Tools_Events::$filterParams),array(false));
		if (in_array($cacheId, $cacheNames)){
			$aliases = $this->_request->getParam('parent');
			if ($aliases){
				if (!is_array($aliases)) $aliases = array($aliases);
				$cache = Zend_Registry::get($cacheId.Tools_Events::$cacheSuffix['alias']);
				$success = true;
				foreach($aliases as $alias){
					if (array_key_exists($alias, $cache)){
						$cache = $cache[$alias];
					}
					else {
						$success = false;
						break;
					}
				}
				if ($success){
					$data = array(
						'success'=>true,
						'data'=>array(),
					);
					$l = $this->view->lang->getLocale();
					foreach($cache as $item){
						if (isset($item['name_'.$l]) && isset($item['alias'])){
							$data['data'][$item['alias']] = $item['name_'.$l];
						}
					}
				}
			}
		}
		echo Zend_Json::encode($data);
	}
	
	protected function setup(){
		$this->initCache();
	}
	
	public function emptyAction(){
	}
}
