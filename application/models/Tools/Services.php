<?php
	class Tools_Services extends Tools_Events {
		protected $_urlParts = array('services', 'search');

		private $_requiredIds = array(
			'category'=>1,
		);

		public function __construct($url, $lang, $p=1){
			parent::__construct($url, $lang, $p);
			self::$filterParams['category'] = 'serviceCategories';
		}

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
