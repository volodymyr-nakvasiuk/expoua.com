<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_CompaniesServicesCats extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('companies_services_cats', 'companies_services_cats_data');

	protected $_db_tables_join_by = array(array('companies_services_cats.id', 'id'));

	protected $_allowed_cols_array = array(array('active', 'companies_id'), array('id', 'languages_id', 'name'));

	protected $_select_list_cols_array = array(array('id', 'active', 'companies_id'), array('name'));

	protected $_sort_col = array('id' => 'DESC');

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->join("companies_data", "companies_data.id = companies_services_cats.companies_id", array("company_name" => 'name'));
		$select->where("companies_data.languages_id = companies_services_cats_data.languages_id");
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->join("companies_data", "companies_data.id = companies_services_cats.companies_id", array("company_name" => 'name'));

		$select->where("companies_data.languages_id = companies_services_cats_data.languages_id");
	}
}