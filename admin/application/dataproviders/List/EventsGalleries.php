<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_EventsGalleries extends List_Abstract {

	protected $_allowed_cols = array('id', 'events_id');

	protected $_db_table = "ExpoPromoter_Opt.events_galleries";

	protected $_select_list_cols = array('id', 'events_id', 'global_sync_id');

	public function insertEntry(Array $data) {
		$res = parent::insertEntry($data);
		
		if ($res == 1 && $this->allowSync == true) {
			$data['id'] = $this->getLastInsertId();
			// По стране организатора принимается решение о синхронизации
			$event = self::_DP("List_Joined_Ep_Events")->getEntry($data['events_id']);
			$brand = self::_DP("List_Joined_Ep_Brands")->getEntry($event['brands_id']);
			$org = self::_DP("List_Joined_Ep_Organizers")->getEntry($brand['organizers_id']);
			$data['sync_cities_id'] = $org['cities_id'];
			
			$this->_AddSyncQueueTask(Sync_Base::TYPE_ADD, $data);
		}
		
		return $res;
	}
	
	public function updateEntry($id, Array $data, Array $extraParams = array()) {
		$res = parent::updateEntry($id, $data, $extraParams);
		
		if ($res != -1 && $this->allowSync == true) {
			$data = $this->getEntry($id, $extraParams);
			// По стране организатора принимается решение о синхронизации
			$event = self::_DP("List_Joined_Ep_Events")->getEntry($data['events_id']);
			$brand = self::_DP("List_Joined_Ep_Brands")->getEntry($event['brands_id']);
			$org = self::_DP("List_Joined_Ep_Organizers")->getEntry($brand['organizers_id']);
			$data['sync_cities_id'] = $org['cities_id'];
				
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
			// По стране организатора принимается решение о синхронизации
			$event = self::_DP("List_Joined_Ep_Events")->getEntry($data['events_id']);
			$brand = self::_DP("List_Joined_Ep_Brands")->getEntry($event['brands_id']);
			$org = self::_DP("List_Joined_Ep_Organizers")->getEntry($brand['organizers_id']);
			$data['sync_cities_id'] = $org['cities_id'];
						
			$this->_AddSyncQueueTask(Sync_Base::TYPE_DELETE, $data);
		}
		
		return $res;
	}
	
	/**
	 * Функция для сохранения интерфейса чтобы система синхронизации работала однородно
	 * со всем типами данных
	 * 
	 * @param array $data
	 */
	public function insertLanguageData($data) {
		return 1;
	}
	
	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$select->join("ExpoPromoter_Opt.events", "events.id = events_galleries.events_id", array('brands_id', 'date_from', 'date_to'));
		$select->join("ExpoPromoter_Opt.brands_data", "events.brands_id = brands_data.id", array('brand_name' => 'name'));
		$select->where("brands_data.languages_id = ?", Zend_Registry::get("language_id"));
	}
}