<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_EventsAdsUsers extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('users_events_ads');

	protected $_db_tables_join_by = array();

	protected $_allowed_cols_array = array(
		array('type', 'service_companies_id', 'events_id', 'events_participants_id', 'period_date_from', 'period_date_to', 'login', 'password')
	);

	protected $_select_list_cols_array = array(array('id', 'type', 'events_id', 'period_date_from', 'period_date_to', 'login'));

	protected $_sort_col = array('id' => 'DESC');

	protected $_prepare_cols = array(
				'service_companies_id' => array('num', null),
				'events_id' => array('num', null),
				'events_participants_id' => array('num', null),
				'period_date_from' => array('date', null),
				'period_date_to' => array('date', null)
	);

	public function authParticipantUser($login, $password) {
		$select = self::$_db->select();

		$select->from('ExpoPromoter_Opt.users_events_ads', array('events_participants_id'));
		$select->where('login =? ', $login);
		$select->where("password = ?", $password);
		$select->where("events_participants_id IS NOT NULL");

		//Zend_Debug::dump($select->__toString());

		try {
			$res = self::$_db->fetchOne($select);
		} catch (Exception $e) {
			echo $e->getMessage();
		}

		return $res;
	}

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->joinLeft("events", "events.id = users_events_ads.events_id", array());
		$select->joinLeft("brands_data", "events.brands_id = brands_data.id AND brands_data.languages_id=1", array("brand_name" => 'name'));
		//$select->where("brands_data.languages_id = events_data.languages_id");
	}

}