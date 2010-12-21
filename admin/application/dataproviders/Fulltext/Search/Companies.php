<?PHP

Zend_Loader::loadClass("Fulltext_Search_Abstract", PATH_DATAPROVIDERS);

class Fulltext_Search_Companies extends Fulltext_Search_Abstract {

	protected $_db_table = 'ExpoPromoter_index.index_words_to_companies';

	public function search($query, $results = 20, $page = 0) {
		$subquery = $this->_getSearchQuery($query);

		if (!$subquery instanceof Zend_Db_Select) {
			return false;
		}

		$subquery->limitPage($page, $results);

		//echo $subquery->__toString();
		$table_company = 'companies_' . $this->_language['code'];

		$select = self::$_db->select();
		$select->from(array('sq' => $subquery), array('words', 'rank'));

		$select->join('ExpoPromoter_MViews.' . $table_company,
			"sq.parent_id = " . $table_company . ".id",
			array('id', 'company_logo' => 'logo', 'cities_id', 'countries_id', 'videos', 'news', 'services', 'employers'));
		
		$select->join('ExpoPromoter_Opt.companies_data',
			$table_company . '.id = companies_data.id',
			array('name', 'description_short' => 'description'));

		$select->joinLeft("ExpoPromoter_Opt.location_cities_data",
			"location_cities_data.id = " . $table_company . ".cities_id AND companies_data.languages_id = location_cities_data.languages_id",
			array('city_name' => 'name'));
		
		$select->joinLeft("ExpoPromoter_Opt.location_countries_data",
			"location_countries_data.id = " . $table_company . ".countries_id AND companies_data.languages_id = location_countries_data.languages_id",
			array('country_name' => 'name'));
		
		$select->where('companies_data.languages_id = ?', $this->_language['id']);

		//echo $select->__toString();

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