<?PHP

Zend_Loader::loadClass("Fulltext_Search_Abstract", PATH_DATAPROVIDERS);

class Fulltext_Search_Companies_Services extends Fulltext_Search_Abstract {

	protected $_db_table = 'ExpoPromoter_index.index_words_to_comservices';

	public function search($query, $results = 20, $page = 0) {
		$subquery = $this->_getSearchQuery($query);

		if (!$subquery instanceof Zend_Db_Select) {
			return false;
		}

		$subquery->limitPage($page, $results);

		//echo $subquery->__toString();

		$select = self::$_db->select();
		$select->from(array('sq' => $subquery), array('words', 'rank'));
		$select->join("ExpoPromoter_Opt.companies_services", "sq.parent_id = companies_services.id", array('id', 'companies_id', 'company_serv_logo' => 'photo'));
		$select->join("ExpoPromoter_Opt.companies_services_data", "companies_services.id = companies_services_data.id", array('name', 'short'));
		$select->join("ExpoPromoter_Opt.companies_services_active", "companies_services_active.id = companies_services.id", array());
		$select->where("ExpoPromoter_Opt.companies_services_active.active = 1");
		$select->where("ExpoPromoter_Opt.companies_services_data.languages_id = ?", $this->_language['id']);

		$select->join('ExpoPromoter_Opt.companies', 'companies.id = companies_services.companies_id', array('cities_id'));
		$select->join('ExpoPromoter_Opt.companies_data', 'companies_data.id = companies.id', array('company_name' => 'name'));
		$select->where("companies_data.languages_id = companies_services_data.languages_id AND companies_services_active.languages_id = companies_services_data.languages_id");

		$select->joinLeft("ExpoPromoter_Opt.location_cities", "location_cities.id = companies.cities_id", array());
		$select->joinLeft("ExpoPromoter_Opt.location_cities_data", "location_cities_data.id = companies.cities_id AND companies_data.languages_id = location_cities_data.languages_id", array('city_name' => 'name'));
		$select->joinLeft("ExpoPromoter_Opt.location_countries_data", "location_countries_data.id = location_cities.countries_id AND companies_data.languages_id = location_countries_data.languages_id", array('country_name' => 'name'));
		
		return self::$_db->fetchAll($select);
	}

	/**
	 * Индексация производится для каждого языка в отдельную таблицу
	 *
	 * @param string $code
	 * @param int $id
	 */
	public function setLanguage($code, $id) {
		parent::setLanguage($code, $id);

		$this->_db_table .= "_" . $code;
	}

}