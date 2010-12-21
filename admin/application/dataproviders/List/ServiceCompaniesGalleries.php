<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_ServiceCompaniesGalleries extends List_Abstract {

	protected $_allowed_cols = array('id', 'servcomps_id');

	protected $_db_table = "ExpoPromoter_Opt.service_companies_galleries";

	protected $_select_list_cols = array('id', 'servcomps_id');

/*
	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$select->join("ExpoPromoter_Opt.service_companies", "service_companies.id = servcomps_id", array('social_organizations_id', 'service_companies_categories_id'));
		$select->where("service_companies_data.languages_id = ?", Zend_Registry::get("language_id"));
	}
*/


/*
  protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
    parent::_SqlAddsWhere($select, $params);
    //Zend_Debug::dump($select);
    
    //print_r($select->__toString());
	}
*/

}

