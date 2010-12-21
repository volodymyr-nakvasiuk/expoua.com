<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_EventsUsers extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('users_events');

	protected $_db_tables_join_by = array();

	protected $_allowed_cols_array = array(
		array('events_id', 'password')
	);

	protected $_select_list_cols_array = array(array('id', 'events_id'));

	protected $_sort_col = array('id' => 'DESC');

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->joinLeft("events", "events.id = users_events.events_id", array());
		$select->joinLeft("brands_data", "events.brands_id = brands_data.id AND brands_data.languages_id=1", array("brand_name" => 'name'));
		//$select->where("brands_data.languages_id = events_data.languages_id");
	}

}