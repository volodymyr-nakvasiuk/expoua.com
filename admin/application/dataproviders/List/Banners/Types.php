<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_Banners_Types extends List_Abstract {

	protected $_allowed_cols = array('id', 'name', 'height', 'width', 'media');

	protected $_db_table = "ExpoPromoter_banners.types";

	protected $_select_list_cols = array('id', 'name', 'media');

}