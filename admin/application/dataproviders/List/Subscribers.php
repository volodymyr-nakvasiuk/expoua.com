<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_Subscribers extends List_Abstract {

	protected $_allowed_cols = array('id', 'active', 'parent', 'type', 'email', 'period', 'languages_id', 'date_last_send');

	protected $_db_table = "ExpoPromoter_Opt.subscribers";

	protected $_select_list_cols = array('id', 'active', 'type', 'email', 'period', 'date_last_send');

	protected $_sort_col = array('id' => 'DESC');

	protected $_prepare_cols = array(
				'parent' => array('num', null)
	);

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$select->joinLeft("ExpoPromoter_Opt.events", "subscribers.parent = events.id", array());
		$select->joinLeft("ExpoPromoter_Opt.brands_data", "events.brands_id = brands_data.id AND brands_data.languages_id = 1", array('brand_name' => 'name'));
	}
}