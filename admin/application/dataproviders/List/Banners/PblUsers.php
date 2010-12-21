<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_Banners_PblUsers extends List_Abstract {

	protected $_allowed_cols = array(
	  'id', 'active', 'login', 'passwd', 'name', 'company', 'countries_id', 'city', 
	  'phone', 'email', 'url', 'deposit', 'discount', 'date_lastlogin', 'date_register'
  );

	protected $_db_table = "ExpoPromoter_banners.pbl_users";

	protected $_select_list_cols = array(
	  'id', 'active', 'login', 'company', 'countries_id', 'name', 'deposit', 'discount', 'date_lastlogin'
  );

}