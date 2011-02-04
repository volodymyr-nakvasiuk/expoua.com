<?php
	class Tools_Online extends Tools_Events {

		protected $_urlParts = array('companies', 'online');

		private $_requiredIds = array(
			'category'=>1,
		);

		protected function _setupFilterData(){
			$notSetRequired = false;
			foreach($this->_requiredIds as $paramName=>$id){
				if (!isset($this->_filterData['filter'][$paramName])){
					$notSetRequired = true; break;
					/*
					$this->_filterData['filter'][$paramName] = $id;
					$cache = Zend_Registry::get(self::$filterParams[$paramName].self::$cacheSuffix['id']);
					$this->_filterData['params'][$paramName] = $cache[$id]['alias'];
					*/
				}
			}
			if ($notSetRequired){
				$urlParts = $this->_urlParts;
				$this->_filterData['url'] = '/'.$this->_lang.'/'.array_shift($urlParts).'/';
				//$this->_filterData['url'] = $this->createFilterUrl($this->_filterData['params'], $this->_lang);
			}
		}
	}
