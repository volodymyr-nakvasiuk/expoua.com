<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_CompaniesEmployers extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('companies_employers', 'companies_employers_data', 'companies_employers_active');

	protected $_db_tables_join_by = array(array('companies_employers.id', 'id'), array('companies_employers.id', 'id'));

	protected $_allowed_cols_array = array(
		array('companies_id', 'photo', 'email', 'phone'),
		array('id', 'languages_id', 'name', 'lastname', 'position'),
		array('id', 'languages_id', 'active'));

	protected $_select_list_cols_array = array(
		array('id', 'companies_id', 'photo'),
		array('name', 'lastname'),
		array('active'));

	protected $_sort_col = array('id' => 'DESC');

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->join("companies_data", "companies_data.id = companies_employers.companies_id", array("company_name" => 'name'));
		$select->where("companies_data.languages_id = companies_employers_data.languages_id");
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->join("companies_data", "companies_data.id = companies_employers.companies_id", array("company_name" => 'name'));
		$select->where("companies_data.languages_id = companies_employers_data.languages_id");
	}
}