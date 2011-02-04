<?php
	class Tools_CompanyServices extends Tools_Events {
		protected $_urlParts = array('companies', 'services');

		private $_requiredIds = array(
			'category'=>1,
		);

		protected function _setupFilterData(){
			$notSetRequired = false;
			foreach($this->_requiredIds as $paramName=>$id){
				if (!isset($this->_filterData['filter'][$paramName])){
					$notSetRequired = true; break;
				}
			}
			if ($notSetRequired){
				$urlParts = $this->_urlParts;
				$this->_filterData['url'] = '/'.$this->_lang.'/'.array_shift($urlParts).'/';
			}
		}
	}
