<?PHP

Zend_Loader::loadClass("Sab_Operator_ModelAbstract", PATH_MODELS);

class Sab_Operator_DraftsModel extends Sab_Operator_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_EventsDrafts';

	public function insertEntry(Array $data_raw, $insertAllLangs = false) {

		if (empty($data_raw['common'])) {
			return -1;
		}

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
				$res = $this->_DP_obj->insertEntry($data);

				if ($res != 1) {
					return $res;
				}

				$id = $this->_DP_obj->getLastInsertId();
				$data_raw['common']['id'] = $id;
				
				//Сохраняем выбранные подкатегории
				if (isset($data['brand_subcategories_id']) && is_array($data['brand_subcategories_id'])) {
					$this->_DP_obj->updateSubCategories($id, $data['brand_subcategories_id']);
				}
			} else {
				$res = $this->_DP_obj->insertLanguageData($data);
			}

			//Добавление не удалось, прекращаем
			if (!$res) {
				return $res;
			}
		}

		return $res;
	}


	public function updateEntry($id, Array $data_raw) {
		if (empty($data_raw['common'])) {
			return -1;
		}

		$langs_list = $this->_DP("List_Languages")->getList();

		foreach ($langs_list['data'] as $lang) {
			if (empty($data_raw[$lang['code']])) {
				continue;
			}

      $this->_DP_limit_params['languages_id'] = $lang['id'];
      
			$data = array_merge($data_raw['common'], $data_raw[$lang['code']]);
			$data = array_merge($data, $this->_DP_limit_params);
			
      if (isset($data['web_address']) && !empty($data['web_address']) && strpos($data['web_address'], 'http://') === false) {
        $data['web_address'] = 'http://' . $data['web_address'];
      } 

      // Zend_Debug::dump($data);
      
		  $res = parent::updateEntry($id, $data);
		}
		
		return 1;
	}


	public function getEventEntry($id) {
		$langs_list = $this->_DP("List_Languages")->getList();

		$res = array();

		foreach ($langs_list['data'] as $lang) {
			$res[$lang['code']] = $this->_DP("List_Joined_Ep_Events")->getEntry($id, array('languages_id' => $lang['id']));
		}

		return $res;
	}


	public function getBrandCategoriesList() {
		$res = $this->_DP("List_Joined_Ep_BrandsCategories")->getList(null, null, array('active'=>1, 'languages_id' => self::$_user_language_id));

		return $res['data'];
	}


	public function getPeriodsList() {
		$res = $this->_DP("List_Joined_Ep_Periods")->getList(null, null, array('active'=>1, 'languages_id' => self::$_user_language_id));

		return $res['data'];
	}


	public function getExpocentersList($city) {
		$res = $this->_DP("List_Joined_Ep_Expocenters")->getList(null, null, array('active'=>1, 'cities_id' => intval($city), 'languages_id' => self::$_user_language_id));

		return $res['data'];
	}


  public function getEntry($id) {
    $langs_list = $this->_DP("List_Languages")->getList();

    $res = array();

    foreach ($langs_list['data'] as $lang) {
      $this->_DP_limit_params['languages_id'] = $lang['id'];
      $res[$lang['code']] = parent::getEntry($id);
    }

    return $res;
  }

}

