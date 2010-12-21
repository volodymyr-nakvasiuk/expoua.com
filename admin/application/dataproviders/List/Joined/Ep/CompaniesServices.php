<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_CompaniesServices extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('companies_services', 'companies_services_data', 'companies_services_active');

	protected $_db_tables_join_by = array(array('companies_services.id', 'id'), array('companies_services.id', 'id'));

	protected $_allowed_cols_array = array(
    // array('companies_id', 'companies_services_cats_id', 'brands_subcategories_id', 'type', 'price', 'photo'),
    array('companies_id', 'brands_subcategories_id', 'type', 'price', 'photo'),
		array('id', 'languages_id', 'name', 'short', 'content'),
		array('id', 'languages_id', 'active'));

	protected $_select_list_cols_array = array(
		array('id', 'companies_id', 'photo'),
		array('name'),
		array('active'));

	protected $_sort_col = array('id' => 'DESC');

	protected $_prepare_cols = array(
		// 'companies_services_cats_id' => array('num', null),
		'brands_subcategories_id' => array('num', null)
	);

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->join("companies_data", "companies_data.id = companies_services.companies_id", array("company_name" => 'name'));
		$select->where("companies_data.languages_id = companies_services_data.languages_id");
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->join("companies_data", "companies_data.id = companies_services.companies_id", array("company_name" => 'name'));

		$select->where("companies_data.languages_id = companies_services_data.languages_id");
	}
}