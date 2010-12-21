<?php
class Db_Banners extends ArOn_Db_Table {

	public function __construct($config = array(), $definition = null) {
		$this->langId = self::$globalLangId;
		self::$globalLangId = DEFAULT_LANG_ID;
		parent::__construct($config, $definition);
	}
	
}