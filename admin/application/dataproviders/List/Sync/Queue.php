<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_Sync_Queue extends List_Abstract {

	protected $_allowed_cols = array('id', 'type', 'parents_id', 'global_sync_id', 'languages_id', 'queue_id',
		'provider', 'status', 'date_added', 'debug');

	protected $_db_table = "ExpoPromoter_Opt.sync_queue";

	protected $_select_list_cols = array('id', 'type', 'parents_id', 'global_sync_id', 'languages_id',
		'provider');

	protected $_prepare_cols = array(
		'parents_global_id' => array('num', null)
	);

	protected $_sort_col = array('id' => 'DESC');
}