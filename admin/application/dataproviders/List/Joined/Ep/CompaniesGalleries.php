<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_CompaniesGalleries extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('companies_galleries', 'companies_galleries_data');

	protected $_db_tables_join_by = array(array('companies_galleries.id', 'id'));

	protected $_allowed_cols_array = array(array('active', 'companies_services_id'), array('id', 'languages_id', 'title', 'description'));

	protected $_select_list_cols_array = array(array('id', 'active', 'companies_services_id'), array('title'));

	protected $_sort_col = array('id' => 'DESC');

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->join("companies_services", "companies_services.id = companies_galleries.companies_services_id", array('companies_id'));
		$select->join("companies_services_data", "companies_services_data.id = companies_services.id", array('service_name' => 'name'));
		$select->join("companies_data", "companies_data.id = companies_services.companies_id", array("company_name" => 'name'));
		$select->where("companies_data.languages_id = companies_galleries_data.languages_id AND companies_services_data.languages_id = companies_galleries_data.languages_id");
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->join("companies_services", "companies_services.id = companies_galleries.companies_services_id", array('companies_id'));
		$select->join("companies_services_data", "companies_services_data.id = companies_services.id", array('service_name' => 'name'));
		$select->join("companies_data", "companies_data.id = companies_services.companies_id", array("company_name" => 'name'));
		$select->where("companies_data.languages_id = companies_galleries_data.languages_id AND companies_services_data.languages_id = companies_galleries_data.languages_id");
	}

	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		parent::_SqlAddsWhere($select, $params);

		if (isset($params['companies_id'])) {
			if ($params['companies_id'] instanceof Zend_Db_Expr) {
				$select->where("companies_services.companies_id ?", $params['companies_id']);
			} else {
				$select->where("companies_services.companies_id = ?", $params['companies_id']);
			}
		}

	}
}