<?php
	class Tools_Online extends Tools_Events {

		private $_requiredIds = array(
			'category'=>1,
		);

		protected $_urlParts = array('companies', 'online');

		protected function _setupFilterData(){
			$notSetRequired = false;
			foreach($this->_requiredIds as $paramName=>$id){
				if (!isset($this->_filterData['filter'][$paramName])){
					$notSetRequired = true;
					$this->_filterData['filter'][$paramName] = $id;
					$cache = Zend_Registry::get(self::$filterParams[$paramName].self::$cacheSuffix['id']);
					$this->_filterData['params'][$paramName] = $cache[$id]['alias'];
				}
			}
			if ($notSetRequired) $this->_filterData['url'] = $this->createFilterUrl($this->_filterData['params'], $this->_lang);
		}
	}
