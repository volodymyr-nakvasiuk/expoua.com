<?PHP

Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_DraftsModel extends Sab_Organizer_ModelAbstract {

  protected $_DP_name = 'List_Joined_Ep_EventsDrafts';

  public function insertEntry(Array $data_raw, $insertAllLangs = false) {

    if (empty($data_raw['common'])) {
      return -1;
    }

    //Zend_Debug::dump($data_raw);

    $data_raw['common']['type'] = 'add';
    $data_raw['common']['status'] = 1;

    $id = null;
    $langs_list = $this->_DP("List_Languages")->getList();

    $ress = false;
    
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

      //Zend_Debug::dump($data);

      if (is_null($id)) {
        $res = $this->_DP_obj->insertEntry($data);

        if ($res != 1) {
          return $res;
        }

        $id = $this->_DP_obj->getLastInsertId();
        $data_raw['common']['id'] = $id;
      } else {
        $res = $this->_DP_obj->insertLanguageData($data);
      }

      //Логотип
      if ($this->updateImageLogo($id, $lang['id'], $lang['code']) == true) {
      	$this->_DP_obj->updateEntry($id, array('logo' => 1), array('languages_id' => $lang['id']));
      }
      
      //Добавление не удалось, прекращаем
      if (!$res) {
        return $res;
      }

      //Сохраняем выбранные подкатегории
      if (isset($data['brand_subcategories_id']) && is_array($data['brand_subcategories_id'])) {
        $this->_DP_obj->updateSubCategories($id, $data['brand_subcategories_id']);
      }
      
      $ress = $ress || ($res == 1);
    }

    if ($ress) {
      // Запись в лог активности организатора
      $this->_DP("List_OrganizersLog")->insertEntry(
        array(
          'type'               => 'event_add', 
          'description'        => 'Создана новая выставка', 
          'users_operators_id' => $this->_user_session->operator->id
        )
      );
    }

    return $res;
  }

  public function updateEntry($id, Array $data_raw) {
    if (empty($data_raw['common'])) {
      return -1;
    }

    $data_raw['common']['status'] = 1;

    $langs_list = $this->_DP("List_Languages")->getList();
    foreach ($langs_list['data'] as $lang) {

      if (empty($data_raw[$lang['code']])) {
        continue;
      }

      $data = array_merge($data_raw[$lang['code']], $data_raw['common']);
      $this->_DP_limit_params['languages_id'] = $lang['id'];

      if (isset($data['web_address']) && !empty($data['web_address']) && strpos($data['web_address'], 'http://') === false) {
        $data['web_address'] = 'http://' . $data['web_address'];
      } 

      $res = parent::updateEntry($id, $data);
    }

    return 1;
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

  public function getEventEntry($id) {
    $langs_list = $this->_DP("List_Languages")->getList();

    $res = array();

    foreach ($langs_list['data'] as $lang) {
      $res[$lang['code']] = $this->_DP("List_Joined_Ep_Events")->getEntry($id, array('languages_id' => $lang['id']));
    }

    return $res;
  }

  public function getPeriodsList() {
    $res = $this->_DP("List_Joined_Ep_Periods")->getList(null, null, array('active'=>1, 'languages_id' => self::$_user_language_id));

    return $res['data'];
  }

  public function getBrandCategoriesList() {
    $res = $this->_DP("List_Joined_Ep_BrandsCategories")->getList(null, null, array('active'=>1, 'languages_id' => self::$_user_language_id));

    return $res['data'];
  }
  
	/**
	 * Добавляет (обновляет) логотип к черновику если он загружен для указанного языка
	 * @param $id
	 * @param $lang_id
	 * @param $lang_code
	 * @return boolean
	 */
	private function updateImageLogo($id, $lang_id, $lang_code) {
		$img_res = null;
		//Подготовка, проверка что файл логотипа загружен
		$logo_code = 'logo_' . $lang_code;
		if (!empty($_FILES) && isset($_FILES[$logo_code]) && $_FILES[$logo_code]['error']==0) {
			$file_fp = $_FILES[$logo_code]['tmp_name'];
			$save_as = PATH_FRONTEND_DATA_IMAGES . "/drafts_events/logo/" . $lang_id . "/" . $id . ".jpg";
			$extraParams = array('image_type' => $_FILES[$logo_code]['type']);
			$img_res = $this->_DP("Filesystem_Images")->thumbnailCreate($file_fp, $save_as, 130, 130, $extraParams);
		}

		return $img_res;
	}
}