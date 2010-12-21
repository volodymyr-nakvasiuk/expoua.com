<?php
require_once(PATH_DATAPROVIDERS . "/Fulltext/Info/Abstract.php");

class Fulltext_Info_Companies extends Fulltext_Info_Abstract {
	
	protected $_db_table = 'ExpoPromoter_index.index_words_to_companies';
	
	public function setLanguage($code, $id) {
		parent::setLanguage($code, $id);
		
		$this->_db_table .= "_" . $code;
	}
	
}