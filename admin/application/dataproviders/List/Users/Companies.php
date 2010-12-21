<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Users/Abstract.php");

class List_Users_Companies extends List_Users_Abstract {

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->join("ExpoPromoter_Opt.companies", 'companies.id = users_sites.companies_id', array());
		$select->join("ExpoPromoter_Opt.companies_data", "companies_data.id = companies.id",
			array("company_name" => 'name'));
		$select->where("companies_data.languages_id = companies.local_languages_id");
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->join("ExpoPromoter_Opt.companies", 'companies.id = users_sites.companies_id', array());
		$select->join("ExpoPromoter_Opt.companies_data", "companies_data.id = companies.id",
			array("company_name" => 'name'));
		$select->where("companies_data.languages_id = companies.local_languages_id");
	}

}