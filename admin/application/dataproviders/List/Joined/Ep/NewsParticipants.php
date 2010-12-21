<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_NewsParticipants extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('news_participants', 'news_participants_data');

	protected $_db_tables_join_by = array(array('news_participants.id', 'id'));

	protected $_allowed_cols_array = array(array('id', 'active', 'events_id', 'events_participants_id', 'email', 'email2', 'web_address'), array('id', 'languages_id', 'title', 'contacts', 'content'));

	protected $_select_list_cols_array = array(array('id', 'active', 'date_created'), array('title'));

	protected $_sort_col = array('id' => 'DESC');

	protected $_prepare_cols = array(
				'events_participants_id' => array('num', null)
	);

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->join("events", "news_participants.events_id=events.id", array());
		$select->join("brands_data", "events.brands_id=brands_data.id AND brands_data.languages_id=news_participants_data.languages_id", array('brand_name' => 'name'));

		$select->joinLeft("events_participants_data", "events_participants_data.id=news_participants.events_participants_id AND events_participants_data.languages_id=news_participants_data.languages_id", array('event_participant_name' => 'name'));
	}
}