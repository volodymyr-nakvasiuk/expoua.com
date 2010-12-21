<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_CompaniesMessages extends List_Abstract {

	protected $_allowed_cols = array('id', 'languages_id', 'companies_id', 'name', 'email', 'phone', 'message');

	protected $_db_table = "ExpoPromoter_Opt.companies_messages";

	protected $_select_list_cols = array('id', 'companies_id', 'name', 'email');

	protected $_sort_col = array('id' => 'DESC');

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		$this->_SqlAddsList($select);
	}

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$select->join("ExpoPromoter_Opt.companies_data", "companies_messages.companies_id = companies_data.id", array('company_name' => 'name'));
		$select->where("companies_data.languages_id = ?", Zend_Registry::get("language_id"));
	}

}