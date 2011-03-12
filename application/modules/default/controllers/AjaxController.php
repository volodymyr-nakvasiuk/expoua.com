<?php

class AjaxController extends Abstract_Controller_AjaxController {

	protected $_redirectUrl = '/error/error/';
	
	public function getAction(){
		$this->resultJSON = array('success'=>false);
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
					$this->resultJSON = array(
						'success'=>true,
						'data'=>array(),
					);
					foreach($cache as $item){
						if (isset($item['name']) && isset($item['alias'])){
							$this->resultJSON['data'][$item['alias']] = $item['name'];
						}
					}
				}
			}
		}
	}
}
