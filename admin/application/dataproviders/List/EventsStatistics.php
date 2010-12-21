<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");
require_once(PATH_WS_CLASSES . "/Net_GeoIP/GeoIP.php");

class List_EventsStatistics extends List_Abstract {

	protected $_allowed_cols = array('id', 'hit_time', 'site_id', 'ip', 'lang', 'events_id');
	protected $_select_list_cols = array('id', 'hit_time', 'site_id', 'ip', 'lang', 'events_id');

	protected $_db_table = "statistic.events_hits";



  public function getList($results_num = null, $page = null, Array $extraParams = array(), Array $sortBy = array()) {
    $result = parent::getList($results_num, $page, $extraParams, $sortBy);

    $countries_names = self::$_db->fetchPairs('SELECT * FROM ExpoPromoter_banners.countries_code_to_name');
    
		if (isset($result['data'])) {
		  foreach ($result['data'] as $key => $el) {
        $country_code = 
          Net_GeoIP::getInstance(PATH_APPLICATION.'/../../ws/data/GeoIP.dat')->lookupCountryCode($el['ip_addr']);
        
        $result['data'][$key]['country_name'] = 
          isset($countries_names[$country_code]) ? $countries_names[$country_code] : "&mdash;";
		  }
		}


    return $result;
  }


	protected function _SqlAddsList(Zend_Db_Select &$select) {
    $lang = Zend_Registry::get('language_id');
    
		$select->joinLeft("ExpoPromoter_Opt.sites_data", "sites_data.id=events_hits.site_id AND sites_data.languages_id = " . intval($lang), array('site_name' => 'name'));
		$select->from("", array('ip_addr' => new Zend_Db_Expr('INET_NTOA(ip)')));
	}


  protected function _SqlAddsDebug(Zend_Db_Select &$select) {
    // Zend_Debug::dump($select->__toString());
  }

}

