<?php
class Db_Banners extends ArOn_Db_Table {

	public function __construct($config = array(), $definition = null) {
		if (!$config[self::ADAPTER]){
			$databases = Zend_Registry::get('databases');
			$config[self::ADAPTER] = $databases['banners'];
		}
		parent::__construct($config, $definition);
	}
	
}
