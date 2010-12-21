<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_EventsFreeTickets extends List_Abstract {

	protected $_allowed_cols = array('time_created', 'events_id');

	protected $_db_table = "ExpoPromoter_Opt.events_tickets";

	protected $_select_list_cols = array('time_created', 'events_id');

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$select->join("ExpoPromoter_Opt.events", "events.id = events_tickets.events_id", array('brands_id', 'date_from', 'date_to'));
		
		$select->join("ExpoPromoter_Opt.brands_data", "events.brands_id = brands_data.id", array('brand_name' => 'name'));
		
		$select->join(
		  "ExpoPromoter_Opt.users_sites",
		  "users_sites.id = events_tickets.users_id", 
		  array(
		    'user_id' => 'id', 
		    'fname', 
		    'lname' => 'name',
		    'positionName' => 'text_dolgnost',
		    'email',
		    'photo_exists',
		    'status', 'functions', 
		  )
		);
		
		$select->joinLeft(
		  "ExpoPromoter_Opt.companies",
		  "companies.id = users_sites.companies_id", 
		  array(
		    'company_phone' => 'phone', 
		    'webAddress' => 'web_address', 
		    'company_email' => 'email',
		    'postcode'
		  )
		);
		
		$select->joinLeft(
		  "ExpoPromoter_Opt.companies_data",
		  "companies_data.id = companies.id", 
		  array(
		    'companyName' => 'name', 
		    'comment' => 'description', 
		    'address'
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
		
		$select->joinLeft("ExpoPromoter_Opt.sites", "sites.id = users_sites.sites_id", array('site_url' => 'url'));
		
		$select->joinLeft("ExpoPromoter_Opt.sites_data", "sites_data.id = sites.id", array('site_name' => 'name'));
		
		$select->where("brands_data.languages_id = ?", Zend_Registry::get("language_id"));
		$select->where("sites_data.languages_id = ?", Zend_Registry::get("language_id"));
		$select->where("companies_data.languages_id = ?", Zend_Registry::get("language_id"));
		$select->where("location_countries_data.languages_id = ?", Zend_Registry::get("language_id"));
		$select->where("location_cities_data.languages_id = ?", Zend_Registry::get("language_id"));

	}


	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
	  return $this->_SqlAddsList($select);
	}
	
}

