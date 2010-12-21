<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_EventsParticipants extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('events_participants', 'events_participants_data');

	protected $_db_tables_join_by = array(array('events_participants.id', 'id'));

	protected $_allowed_cols_array = array(
			array('id', 'active', 'events_id', 'brands_categories_id', 'cities_id', 'logo', 'unique_id'),
			array('id', 'languages_id', 'name', 'description', 'address', 'postcode', 'phone', 'fax', 'email', 'email2', 'web_address')
		);

	protected $_select_list_cols_array = array(array('id', 'active', 'events_id'), array('name'));

	protected $_sort_col = array('id' => 'DESC');

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->join("events", "events.id = events_participants.events_id", array('event_date_from' => 'date_from', 'event_date_to' => 'date_to'));
		$select->join("brands_data", "events.brands_id = brands_data.id", array('brand_name' => 'name'));

		$select->where("brands_data.languages_id = events_participants_data.languages_id");
	}

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->join("location_cities_data", "events_participants.cities_id = location_cities_data.id", array("city_name" => 'name'));
		$select->where("location_cities_data.languages_id = events_participants_data.languages_id");
	}

}