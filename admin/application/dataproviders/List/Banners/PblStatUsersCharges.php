<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_Banners_PblStatUsersCharges extends List_Abstract {

	protected $_allowed_cols = array('id', 'users_id', 'value');

	protected $_db_table = "ExpoPromoter_banners.pbl_stat_users_charges";

	protected $_select_list_cols = array('id', 'users_id', 'date_charge', 'value');

}