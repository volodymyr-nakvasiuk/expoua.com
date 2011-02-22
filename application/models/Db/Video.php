<?php
class Db_Video extends ArOn_Db_Table {

	public function __construct($config = array(), $definition = null) {
		if (!$config[self::ADAPTER]){
			$databases = Zend_Registry::get('databases');
			$config[self::ADAPTER] = $databases['video'];
		}
		parent::__construct($config, $definition);
	}

}
