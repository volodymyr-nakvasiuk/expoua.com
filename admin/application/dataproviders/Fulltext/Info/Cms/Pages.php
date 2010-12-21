<?php
require_once(PATH_DATAPROVIDERS . "/Fulltext/Info/Abstract.php");

class Fulltext_Info_Cms_Pages extends Fulltext_Info_Abstract {
	
	protected $_db_table = 'index_words_to_cms_pages';
	
	public function setLanguage($code, $id) {
		parent::setLanguage($code, $id);
		
		$this->_db_table .= "_" . $code;
	}
	
}