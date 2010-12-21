<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_TourRequests extends List_Abstract {

	protected $_allowed_cols = array('id', 'sites_id', 'languages_id', 'events_id', 'dest_country', 'dest_city', 'date_from', 'date_to', 'hotel1', 'hotel2', 'hotel3', 'hotel4', 'hotel5', 'persons', 'rooms', 'cat_single', 'cat_double', 'cat_triple', 'price1', 'price2', 'price3', 'price4', 'transport1', 'transport2', 'transport3', 'transport4', 'aux_transfert', 'aux_visa', 'aux_translator', 'aux_excursion', 'company', 'contact_name', 'phone', 'country', 'city', 'email', 'notes', 'request_time', 'junk');

	protected $_db_table = "ExpoPromoter_Opt.business_tour_requests";

	protected $_select_list_cols = array('id', 'sites_id', 'events_id', 'dest_country', 'dest_city', 'date_from', 'date_to', 'hotel1', 'hotel2', 'hotel3', 'hotel4', 'hotel5', 'persons', 'rooms', 'cat_single', 'cat_double', 'cat_triple', 'price1', 'price2', 'price3', 'price4', 'transport1', 'transport2', 'transport3', 'transport4', 'aux_transfert', 'aux_visa', 'aux_translator', 'aux_excursion', 'company', 'contact_name', 'phone', 'country', 'city', 'email', 'notes', 'request_time', 'junk');

	protected $_sort_col = array('request_time' => 'DESC');



	protected function _SqlAddsList(Zend_Db_Select &$select) {
	  $lang = Zend_Registry::get('language_id');
	  
		$select->join(
		  "ExpoPromoter_Opt.languages",
		  "business_tour_requests.languages_id = languages.id", 
		  array('language_name' => 'name')
		);
		
		$select->join(
		  "ExpoPromoter_Opt.sites_data",
		  "business_tour_requests.sites_id = sites_data.id AND sites_data.languages_id = '$lang'", 
		  array('site_name' => 'name')
		);

		$select->joinLeft(
		  "ExpoPromoter_Opt.events",
		  "business_tour_requests.events_id = events.id", 
		  array('event_date_from' => 'date_from', 'event_date_to' => 'date_to')
		);

		$select->joinLeft(
		  "ExpoPromoter_Opt.brands_data",
		  "events.brands_id = brands_data.id AND brands_data.languages_id = '$lang'", 
		  array('brand_name' => 'name')
		);
	}
	

  protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
    $hide_country = Zend_Registry::get('hide_country');
    $select->where("business_tour_requests.country != ?", $hide_country);
  
    parent::_SqlAddsWhere($select, $params);
  }
 
	
}

