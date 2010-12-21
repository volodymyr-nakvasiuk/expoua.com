<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_CompaniesNews extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('companies_news', 'companies_news_data', 'companies_news_active');

	protected $_db_tables_join_by = array(array('companies_news.id', 'id'), array('companies_news.id', 'id'));

	protected $_allowed_cols_array = array(
		array('companies_id', 'countries_id', 'events_id', 'logo', 'date_public'),
		array('id', 'languages_id', 'name', 'content'),
		array('id', 'languages_id', 'active'));

	protected $_select_list_cols_array = array(
		array('id', 'companies_id', 'logo', 'date_public'),
		array('name'),
		array('active'));

	protected $_sort_col = array('id' => 'DESC');

	protected $_prepare_cols = array(
		'countries_id' => array('num', null),
		'events_id' => array('num', null)
	);

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->join("companies_data", "companies_data.id = companies_news.companies_id", array("company_name" => 'name'));
		$select->where("companies_data.languages_id = companies_news_data.languages_id");
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->join("companies_data", "companies_data.id = companies_news.companies_id", array("company_name" => 'name'));
		$select->joinLeft("location_countries_data", "companies_news.countries_id = location_countries_data.id AND location_countries_data.languages_id=companies_news_data.languages_id", array("country_name" => 'name'));
		$select->joinLeft("view_brand_event", "companies_news.events_id = view_brand_event.id AND view_brand_event.languages_id=companies_news_data.languages_id", array('brand_name', 'event_date_from' => 'date_from', 'event_date_to' => 'date_to'));

		$select->where("companies_data.languages_id = companies_news_data.languages_id");
	}

}