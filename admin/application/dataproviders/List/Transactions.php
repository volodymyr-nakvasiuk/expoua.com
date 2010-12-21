<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_Transactions extends List_Abstract {

	protected $_allowed_cols = array('id', 'type', 'source', 'summ', 'date', 'id_depend',
  		'id_site', 'id_buyer', 'id_registration', 'id_advertiser');

	protected $_db_table = "ExpoPromoter_Opt.transactions";

	protected $_select_list_cols = array('id', 'type', 'source', 'summ', 'date', 'id_depend',
  		'id_site', 'id_buyer', 'id_registration', 'id_advertiser');

	protected $_sort_col = array('id' => 'DESC');

}