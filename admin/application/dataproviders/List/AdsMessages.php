<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_AdsMessages extends List_Abstract {

	protected $_allowed_cols = array('id', 'events_ads_id', 'news_participants_id', 'status', 'email', 'message');

	protected $_db_table = "ExpoPromoter_Opt.events_ads_messages";

	protected $_select_list_cols = array('id', 'events_ads_id', 'news_participants_id', 'status', 'email');

	protected $_prepare_cols = array('status' => array('num', 0));

}