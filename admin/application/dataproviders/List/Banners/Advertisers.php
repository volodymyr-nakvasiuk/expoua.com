<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_Banners_Advertisers extends List_Abstract {

	protected $_allowed_cols = array('id', 'name', 'description');

	protected $_db_table = "ExpoPromoter_banners.advertisers";

	protected $_select_list_cols = array('id', 'name');

	protected $_sort_col = array('id' => 'DESC');

}