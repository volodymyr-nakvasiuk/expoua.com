<?PHP

Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_NewsModel extends Sab_Organizer_ModelAbstract {

  protected $_DP_name = 'List_Joined_Ep_News';

  public function insertEntry(Array $data_raw, $insertAllLangs = false) {

    if (empty($data_raw['common'])) {
      return -1;
    }

    $data_raw['common']['active'] = 0;

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
        $res = $this->_DP_obj->insertEntry($data);

        if ($res != 1) {
          return $res;
        }

        $id = $this->_DP_obj->getLastInsertId();
        $data_raw['common']['id'] = $id;
      } else {
        $res = $this->_DP_obj->insertLanguageData($data);
      }

      //Добавление не удалось, прекращаем
      if (!$res) {
        return $res;
      }
    }

    // Запись в лог активности организатора
    $this->_DP("List_OrganizersLog")->insertEntry(
      array(
        'type'               => 'news_add', 
        'description'        => 'Добавлена новость', 
        'users_operators_id' => $this->_user_session->operator->id
      )
    );

    return $res;
  }

  public function getBrandCategoriesList() {
    return $this->_DP("List_Joined_Ep_BrandsCategories")->getList(null, null, array('active'=>1, 'languages_id' => self::$_user_language_id), array("name" => "ASC"));
  }

  public function getCountriesList() {
    return $this->_DP("List_Joined_Ep_Countries")->getList(null, null, array('active'=>1, 'languages_id' => self::$_user_language_id), array("name" => "ASC"));
  }
}