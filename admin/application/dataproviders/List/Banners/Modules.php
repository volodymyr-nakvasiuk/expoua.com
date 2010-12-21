<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_Banners_Modules extends List_Abstract {

	protected $_allowed_cols = array('id', 'name', 'code');

	protected $_db_table = "ExpoPromoter_banners.modules";

	protected $_select_list_cols = array('id', 'name');

}