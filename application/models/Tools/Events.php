<?php
	class Tools_Events{
		static $cacheSuffix = array(
			'id'=>'CacheByIds',
			'alias'=>'CacheByNames',
		);
		protected $_p = false;
		protected $_url = false;
		protected $_lang = false;
		protected $_filterData = false;
		protected $_urlParts = array('events');
		static $filterParams = array(
			'region'=>'regions',
			'country'=>'countries',
			'city'=>'cities',
			'category'=>'categories',
			'subcategory'=>'subcategories',
			'from'=>false,
			'till'=>false,
			'search'=>false,
		);

		public function __construct($url, $lang, $p=1){
			$this->_url = $url;
			$this->_lang = $lang;
			$this->_p = $p;
		}

		public function getFilterData(){
			if (!$this->_filterData) $this->_setFilterData();
			return $this->_filterData;
		}

		protected function _setFilterData(){
			$parts = explode('/', $this->_url);
			$data = array('url'=>false,'params'=>array(),'filter'=>array());
			foreach ($parts as $part){
				$tmp = explode('-',$part);
				$paramName = array_shift($tmp);
				if (array_key_exists($paramName, self::$filterParams) && $tmp){
					$paramValue = implode('-',$tmp);
					if ($paramValue){
						$data['params'][$paramName] = strtolower($paramValue);
					}
				}
			}
			list($data['params'], $data['filter']) = self::setupFilter($data['params']);
			$data['filter']['p'] = $this->_p;
			$data['url'] = $this->createFilterUrl($data['params'], $this->_lang);
			
			$this->_filterData = $data;
		}

		static function setupFilter($data){
			$fData = array();

			$paramName = 'from';
			if (isset($data[$paramName])){
				$time = strtotime($data[$paramName]);
				if ($time) $fData[$paramName] = date('Y-m-d', $time);
			}

			$paramName = 'till';
			if (isset($data[$paramName])){
				$time = strtotime($data[$paramName]);
				if ($time) $fData[$paramName] = date('Y-m-d', $time);
			}

			$paramName = 'search';
			if (isset($data[$paramName])){
				$fData[$paramName] = $data[$paramName];
			}

			$gData = $fData;

			$paramName = 'region';
			if (isset($data[$paramName])){
				$cache = Zend_Registry::get(self::$filterParams[$paramName].self::$cacheSuffix['alias']);
				if (array_key_exists($data[$paramName], $cache)){
					$fData[$paramName] = $data[$paramName];
					$gData[$paramName] = $cache[$data[$paramName]]['id'];

					$paramName_1 = $paramName;
					$paramName = 'country';
					if (isset($data[$paramName])){
						$cache = Zend_Registry::get(self::$filterParams[$paramName].self::$cacheSuffix['alias']);
						if (array_key_exists($data[$paramName], $cache[$fData[$paramName_1]])){
							$fData[$paramName] = $data[$paramName];
							$gData[$paramName] = $cache[$fData[$paramName_1]][$data[$paramName]]['id'];
							unset($gData[$paramName_1]);

							$paramName_2 = $paramName;
							$paramName = 'city';
							if (isset($data[$paramName])){
								$cache = Zend_Registry::get(self::$filterParams[$paramName].self::$cacheSuffix['alias']);
								if (array_key_exists($data[$paramName], $cache[$fData[$paramName_1]][$fData[$paramName_2]])){
									$fData[$paramName] = $data[$paramName];
									$gData[$paramName] = $cache[$fData[$paramName_1]][$fData[$paramName_2]][$data[$paramName]]['id'];
									unset($gData[$paramName_2]);
								}
							}
						}
					}
				}
			}

			$paramName = 'category';
			if (isset($data[$paramName])){
				$cache = Zend_Registry::get(self::$filterParams[$paramName].self::$cacheSuffix['alias']);
				if (array_key_exists($data[$paramName], $cache)){
					$fData[$paramName] = $data[$paramName];
					$gData[$paramName] = $cache[$data[$paramName]]['id'];

					$paramName_1 = $paramName;
					$paramName = 'subcategory';
					if (isset($data[$paramName])){
						$cache = Zend_Registry::get(self::$filterParams[$paramName].self::$cacheSuffix['alias']);
						if (array_key_exists($data[$paramName], $cache[$fData[$paramName_1]])){
							$fData[$paramName] = $data[$paramName];
							$gData[$paramName] = $cache[$fData[$paramName_1]][$data[$paramName]]['id'];
							unset($gData[$paramName_1]);
						}
					}
				}
			}

			return array($fData, $gData);
		}

		public function createFilterUrl($data, $lang){
			$url = $this->_urlParts;
			foreach (self::$filterParams as $paramName=>$cacheName){
				if (isset($data[$paramName])) $url[] =  $paramName.'-'.$data[$paramName];
			}
			return '/'.$lang.'/'.implode('/',$url).'/';
		}

		static function date($locale, $format, $timestamp=null){
			if (!$timestamp) $timestamp=time();
			switch ($locale){
				case 'ru':
					return ArOn_Crud_Tools_Date::russian_date($format, $timestamp);
					break;
				case 'en':
				default:
					return date($format, $timestamp);
			}
		}
	}
