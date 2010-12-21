<?PHP
Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_News extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('news', 'news_data');

	protected $_db_tables_join_by = array(array('news.id', 'id'));

	protected $_allowed_cols_array = array(
		array('id', 'brands_categories_id', 'brands_id', 'countries_id', 'organizers_id', 'expocenters_id',
			'service_companies_id', 'events_pariticipants_id', 'user_creator_id', 'date_public',
			'create_languages_id', 'global_sync_id'),
		array('id', 'languages_id', 'active', 'name', 'preambula', 'content')
	);

	protected $_select_list_cols_array = array(array('id', 'date_created'), array('active', 'name'));

	protected $_sort_col = array('id' => 'DESC');

	protected $_prepare_cols = array(
				'brands_categories_id' => array('num', null),
				'brands_id' => array('num', null),
				'countries_id' => array('num', null),
				'organizers_id' => array('num', null),
				'expocenters_id' => array('num', null),
				'service_companies_id' => array('num', null),
				'events_pariticipants_id' => array('num', null),
				'user_creator_id' => array('num', null),
				'global_sync_id' => array('num', null)
	);
	
	public function insertEntry(Array $data) {
		$res = parent::insertEntry($data);
		
		if ($res == 1 && $this->allowSync == true) {
			$data['id'] = $this->getLastInsertId();
			$this->_AddSyncQueueTask(Sync_Base::TYPE_ADD, $data);
		}
		
		return $res;
	}
	
	public function updateEntry($id, Array $data, Array $extraParams = array()) {
		$res = parent::updateEntry($id, $data, $extraParams);
		
		if ($res == 1 && $this->allowSync == true) {
			$data = $this->getEntry($id, $extraParams);
			$this->_AddSyncQueueTask(Sync_Base::TYPE_UPDATE, $data);
		}
		
		return $res;
	}
	
	public function deleteEntry(Array $id = array(), Array $extraParams = array()) {
		$data = array();
		if (sizeof($id) > 0) {
			$data = $this->getEntry($id[0], $extraParams);
		}
		
		$res = parent::deleteEntry($id, $extraParams);
		
		if ($res == 1 && !empty($data) && $this->allowSync == true) {
			$this->_AddSyncQueueTask(Sync_Base::TYPE_DELETE, $data);
		}
		
		return $res;
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		parent::_SqlAddsEntry($select);

		$select->joinLeft("brands_data",
			"news.brands_id=brands_data.id AND news_data.languages_id=brands_data.languages_id",
			array('brand_name' => 'name')
		);

		$select->joinLeft("organizers_data",
			"news.organizers_id=organizers_data.id AND news_data.languages_id=organizers_data.languages_id",
			array('organizer_name' => 'name')
		);

		$select->joinLeft("expocenters_data",
			"news.expocenters_id=expocenters_data.id AND news_data.languages_id=expocenters_data.languages_id",
			array('expocenter_name' => 'name')
		);

		$select->joinLeft("service_companies_data",
			"news.service_companies_id=service_companies_data.id AND news_data.languages_id=service_companies_data.languages_id",
			array('service_company_name' => 'name')
		);

		$select->joinLeft("events_participants_data",
			"news.events_pariticipants_id=events_participants_data.id AND news_data.languages_id=events_participants_data.languages_id",
			array('event_participant_name' => 'name')
		);
	}
}