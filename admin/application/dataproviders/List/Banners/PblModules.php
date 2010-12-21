<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Joined/Abstract.php");

class List_Banners_PblModules extends List_Joined_Abstract {

	protected $_db_tables_array = array('ExpoPromoter_banners.pbl_modules', 'ExpoPromoter_banners.pbl_modules_data');

	protected $_db_tables_join_by = array(array('pbl_modules.id', 'id'));

	protected $_allowed_cols_array = array(array('id', 'code'), array('id', 'languages_id', 'name'));

	protected $_select_list_cols_array = array(array('id'), array('name'));

}