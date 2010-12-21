<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_EventsAds extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('events_ads', 'events_ads_data');

	protected $_db_tables_join_by = array(array('events_ads.id', 'id'));

	protected $_allowed_cols_array = array(array('id', 'type', 'active', 'events_id', 'service_companies_id', 'events_participants_id', 'email', 'pin', 'date_pay'), array('id', 'languages_id', 'name', 'description_short', 'description'));

	protected $_select_list_cols_array = array(array('id', 'active', 'date_pay', 'date_pay_extra', 'date_pay_firstplace', 'events_id'), array('name'));

	protected $_sort_col = array('id' => 'DESC');

	protected $_prepare_cols = array(
				'service_companies_id' => array('num', null),
				'events_participants_id' => array('num', null)
	);

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->joinLeft("events", "events_ads.events_id = events.id", array('brands_id', 'event_date_from' => 'date_from', 'event_date_to' => 'date_to'));
		$select->joinLeft("brands_data", "events.brands_id = brands_data.id AND brands_data.languages_id = events_ads_data.languages_id", array('brand_name' => 'name'));

		$select->joinLeft("service_companies_data", "service_companies_data.id=events_ads.service_companies_id AND service_companies_data.languages_id = events_ads_data.languages_id", array("service_company_name" => 'name'));

		$select->joinLeft("events_participants_data", "events_participants_data.id = events_ads.events_participants_id AND events_participants_data.languages_id = events_ads_data.languages_id", array("event_participant_name" => 'name'));
	}
}