<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_VenueMessages extends List_Abstract {

  protected $_allowed_cols = array('id', 'expocenters_id', 'message', 'answer');

  protected $_db_table = "ExpoPromoter_Opt.expocenters_messages";

  protected $_select_list_cols = array('id', 'expocenters_id', 'message', 'answer', 'date_posted');

  protected $_sort_col = array('date_posted' => 'DESC');

  protected function _SqlAddsList(Zend_Db_Select &$select) {
    $select->join(
      "ExpoPromoter_Opt.expocenters_data",
      "expocenters_data.id = expocenters_messages.expocenters_id", 
      array('contact_name' => 'contact_name')
    );

    $select->join(
      "ExpoPromoter_Opt.expocenters",
      "expocenters.id = expocenters_messages.expocenters_id", 
      array('login')
    );
  }

}