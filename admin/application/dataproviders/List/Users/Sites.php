<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Users/Abstract.php");

class List_Users_Sites extends List_Users_Abstract {
	
	protected function _SqlAddsList(Zend_Db_Select &$select) {
		
		$select->joinLeft(
		  "ExpoPromoter_Opt.companies",
		  "companies.id = users_sites.companies_id", 
		  array(
		    'company_phone' => 'phone', 
		    'webAddress' => 'web_address', 
		    'company_email' => 'email',
		  )
		);
		
		$select->joinLeft(
		  "ExpoPromoter_Opt.companies_data",
		  "companies_data.id = companies.id", 
		  array(
		    'companyName' => 'name', 
		    'comment' => 'description', 
		  )
		);
		
		$select->joinLeft(
		  "ExpoPromoter_Opt.location_countries_data",
		  "location_countries_data.id = users_sites.countries_id", 
		  array(
		    'countryName' => 'name', 
		  )
		);
		
		$select->joinLeft(
		  "ExpoPromoter_Opt.location_cities_data",
		  "location_cities_data.id = companies.cities_id", 
		  array(
		    'cityName' => 'name', 
		  )
		);
				
		$select->where("companies_data.languages_id = ?", Zend_Registry::get("language_id"));

		$select->where("location_countries_data.languages_id = ?", Zend_Registry::get("language_id"));
		$select->where("location_cities_data.languages_id = ?", Zend_Registry::get("language_id"));

	}
	
	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		$this->_SqlAddsList($select);
	}
	
}
