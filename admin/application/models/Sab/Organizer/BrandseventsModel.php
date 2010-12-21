<?PHP

Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_BrandseventsModel extends Sab_Organizer_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_BrandPlusEvent';

	public function getDraftsList() {
		return $this->_DP("List_Joined_Ep_EventsDrafts")->getList(null, null, $this->_DP_limit_params, array('status' => 'ASC'));
	}

	public function getList($page = null, $sort = null, $search = null) {
		//$tmp = new Zend_Db_Expr(" > NOW()");
		//$search['date_to'] = new Zend_Db_Expr(" > NOW()");

		$search[] = array('column' => 'date_to', 'value' => date("Y-m-d"), 'type' => '>');

		$res = parent::getList($page, $sort, $search);

		/*
		$brands = array();
		foreach ($res['data'] as $el) {
			if (in_array($el['brands_id'], $brands)) {
				unset($res['data'][$el['id']]);
			} else {
				$brands[] = $el['brands_id'];
			}
		}*/

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

      if (isset($data['web_address']) && !empty($data['web_address']) && strpos($data['web_address'], 'http://') === false) {
        $data['web_address'] = 'http://' . $data['web_address'];
      } 

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

    // Запись в лог активности организатора
    $this->_DP("List_OrganizersLog")->insertEntry(
      array(
        'type'               => 'event_update', 
        'description'        => 'Обновлена выставка ID ' . $id, 
        'users_operators_id' => $this->_user_session->operator->id
      )
    );

		return $res;
	}

	public function getEntry($id) {
		$this->checkUserEventPermission($id);

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