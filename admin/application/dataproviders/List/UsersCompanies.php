<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_UsersCompanies extends List_Abstract {

	protected $_allowed_cols = array('id', 'active', 'login', 'passwd', 'name', 'email', 'companies_id');

	protected $_db_table = "ExpoPromoter_Opt.users_companies";

	protected $_select_list_cols = array('id', 'active', 'login', 'name', 'companies_id');

	protected $_sort_col = array('id' => 'DESC');

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->join("ExpoPromoter_Opt.companies_data", "companies_data.id = users_companies.companies_id", array("company_name" => 'name'));
		$select->where("companies_data.languages_id = 1");
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->join("ExpoPromoter_Opt.companies_data", "companies_data.id = users_companies.companies_id", array("company_name" => 'name'));
		$select->where("companies_data.languages_id = 1");
	}
	

  protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
    parent::_SqlAddsWhere($select, $params);

    if (isset($params['code'])) {
      $select->where("MD5(CONCAT(login, passwd)) = ?", $params['code']);
    }
  }

	

}