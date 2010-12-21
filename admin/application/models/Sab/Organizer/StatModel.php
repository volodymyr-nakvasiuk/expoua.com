<?PHP

Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_StatModel extends Sab_Organizer_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_BrandPlusEvent';

	public $detailed = false;


	public function getStatList($id, $page = 1) {
		$result = $this->_DP("List_EventsStatistics")->getList($this->forceListResults, $page, array('events_id' => $id), array('hit_time' => 'DESC'));

		return $result;
	}


	public function getDetailedList() {
		$events_list = $this->getList();
		 
		if (!empty($events_list['data'])) {
			foreach ($events_list['data'] as $k => $event) {
				$res = $this->_DP("List_EventsStatistics")->getList(
					null, null,
					array('events_id' => $event['id']),
					array('hit_time' => 'DESC')
				);

				if (!empty($res['data'])) $events_list[$k]['hits'] = $res['data'];

			}

			return $events_list['data'];
		} else {
			return array();
		}
	}


	public function getRedirectStatList($id, $page = 1) {
		$result = $this->_DP("List_EventsRedirectStatistics")->getList($this->forceListResults, $page, array('events_id' => $id), array('redirect_time' => 'DESC'));

		return $result;
	}


	public function getList($page = null, $sort = null, $search = null) {
		$res = parent::getList($page, $sort, $search);

		if ($this->detailed) {
			// Zend_Debug::dump($res);

			if (!empty($res['data'])) {
				foreach ($res['data'] as $k => $event) {
					$res1 = $this->_DP("List_EventsStatistics")->getList(
						null, null,
						array('events_id' => $event['id']),
						array('hit_time' => 'DESC')
					);

					if (!empty($res1['data'])) $res['data'][$k]['hits'] = $res1['data'];
				}
			}
		}

		return $res;
	}

	public function updateEntry($id, Array $data_raw) {
		if (empty($data_raw['common'])) {
			return -1;
		}

		$data_raw['common']['type'] = 'edit';
		$data_raw['common']['status'] = 1;
		$data_raw['common']['events_id'] = $id;
		$data_raw['common']['brand_organizers_id'] = $this->getUserOrganizerId();

		$id = null;
		$langs_list = $this->_DP("List_Languages")->getList();
		foreach ($langs_list['data'] as $lang) {

			if (empty($data_raw[$lang['code']])) {
				continue;
			}

			$data = array_merge($data_raw[$lang['code']], $data_raw['common']);
			$data = array_merge($data, $this->_DP_limit_params);
			$data['languages_id'] = $lang['id'];

			if (is_null($id)) {
				$res = $this->_DP('List_Joined_Ep_EventsDrafts')->insertEntry($data);

				if ($res != 1) {
					return $res;
				}

				$id = $this->_DP('List_Joined_Ep_EventsDrafts')->getLastInsertId();
				$data_raw['common']['id'] = $id;
			} else {
				$res = $this->_DP('List_Joined_Ep_EventsDrafts')->insertLanguageData($data);
			}

			//Добавление не удалось, прекращаем
			if (!$res) {
				return $res;
			}
		}

		return $res;
	}

	public function getEntry($id) {
		$this->checkUserEventPermission($id);

		$event_entry = parent::getEntry($id);

		$langs_list = $this->getLanguagesList();
		foreach ($langs_list as $el) {
			$event_entry[$el['code']] = $this->_DP("List_Joined_Ep_Events")->getEntry($id, array('languages_id' => $el['id']));
		}

		return $event_entry;
	}

	public function getDistinctDraftsBrandsList() {
		//Zend_Debug::dump($this->_user_session->operator->id);
		return $this->_DP("List_Joined_Ep_EventsDrafts")->
			getDistinctDraftsBrandsList($this->_user_session->operator->id);
	}

	public function getPeriodsList() {
		$res = $this->_DP("List_Joined_Ep_Periods")->getList(null, null, array('active'=>1, 'languages_id' => self::$_user_language_id));

		return $res['data'];
	}

	public function getBrandCategoriesList() {
		$res = $this->_DP("List_Joined_Ep_BrandsCategories")->getList(null, null, array('active'=>1, 'languages_id' => self::$_user_language_id));

		return $res['data'];
	}

}