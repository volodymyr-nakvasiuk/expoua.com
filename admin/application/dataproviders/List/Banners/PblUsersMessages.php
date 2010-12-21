<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_Banners_PblUsersMessages extends List_Abstract {

  protected $_allowed_cols = array('id', 'users_id', 'message', 'answer');

  protected $_db_table = "ExpoPromoter_banners.pbl_users_messages";

  protected $_select_list_cols = array('id', 'users_id', 'message', 'answer', 'date_posted');

  protected $_sort_col = array('date_posted' => 'DESC');

  protected function _SqlAddsList(Zend_Db_Select &$select) {
    $select->join("ExpoPromoter_banners.pbl_users", "pbl_users.id = pbl_users_messages.users_id", array('login', 'advertiser_name' => 'name', 'advertiser_company' => 'company', 'email' => 'email'));
  }

  protected function _SqlAddsEntry(Zend_Db_Select &$select) {
    $select->join("ExpoPromoter_banners.pbl_users", "pbl_users.id = pbl_users_messages.users_id", array('login', 'advertiser_name' => 'name', 'advertiser_company' => 'company', 'email' => 'email'));
  }

}