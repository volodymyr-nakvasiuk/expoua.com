<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_Banners_Companies extends List_Abstract {

	protected $_allowed_cols = array('id', 'advertisers_id', 'name', 'description');

	protected $_db_table = "ExpoPromoter_banners.companies";

	protected $_select_list_cols = array('id', 'name');

	protected $_sort_col = array('id' => 'DESC');

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$select->join("advertisers",
			"companies.advertisers_id = advertisers.id",
			array('advertiser_name' => 'name'), 'ExpoPromoter_banners');
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		$this->_SqlAddsList($select);
	}
}