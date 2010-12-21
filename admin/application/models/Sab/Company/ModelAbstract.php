<?PHP

Zend_Loader::loadClass("Sab_ListModelAbstract", PATH_MODELS);

abstract class Sab_Company_ModelAbstract extends Sab_ListModelAbstract {

  /**
  * Экземпляр объекта сессии пользователя
  *
  * @var Zend_Session_Namespace
  */
  protected $_user_session = null;

  public function init() {
    //Сперва вызываем функцию инициализации родителя
    parent::init();

    //Устанавливаем пространство имен аутентификации админки
    Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('Auth_Companies_NS'));

    $this->_user_session = new Zend_Session_Namespace("Shelby_auth_companies", true);

    //Проверяем, зарегистрирован ли пользватель
    if (!Zend_Auth::getInstance()->hasIdentity()) {
      return false;
    }

    //Устанавливаем глобальные ограничивающие параметры

    $this->_DP_limit_params = array(
      'languages_id' => self::$_user_language_id,
      'companies_id' => $this->_user_session->companies->companies_id);
//			'users_companies_id' => $this->_user_session->companies->id);
  }

  /**
  * Проверяем сессию пользователя.
  * Если пользователь зарегистрирован функция возвращает его логин, иначе false;
  *
  * @return boolean|string
  */
  public function checkUserSession() {

    $identity = Zend_Auth::getInstance()->hasIdentity();

    //Сперва проверяем зарегистрирован ли пользователь
    if ($identity === true) {
      $identity = Zend_Auth::getInstance()->getIdentity();
    } else {
      return false;
    }

    return $identity;
  }

  public function getUserCompanyId() {
    return $this->_user_session->companies->companies_id;
  }

  public function getCompanyData() {
    return $this->_user_session->company_data;
  }

  /**
  * Переопределяем функцию получения списка языков
  * Возвращает английский и локальный (выбранный) язык
  *
  * @return unknown
  */
  public function getUserLanguagesList() {
/*
    $extraParams['id'] = array(2);

    if (!empty($this->_user_session->companies->local_languages_id)) {
      $extraParams['id'][] = $this->_user_session->companies->local_languages_id;
    }
*/

    $extraParams = array('id' => array(1,2));
    //$extraParams['id'] = 2;

    $list = $this->_DP("List_Languages")->getList(null, null, $extraParams);
    //Zend_Debug::dump($list);
    return $list['data'];
  }

  //Переопределяем классы для управления данными, делаем их мультиязычными

  public function insertEntry(Array $data_raw, $insertAllLangs = false) {

    if (empty($data_raw['common'])) {
      return -1;
    }

    /*if (!isset($data_raw['common']['active'])) {
      $data_raw['common']['active'] = 1;
    }*/

    $data_raw['common']['companies_id'] = $this->getUserCompanyId();

    $id = null;
    $langs_list = $this->getUserLanguagesList();
    foreach ($langs_list as $lang) {

      if (empty($data_raw[$lang['code']])) {
        continue;
      }

      $data = array_merge($data_raw[$lang['code']], $data_raw['common']);
      $data = array_merge($data, $this->_DP_limit_params);
      $data['languages_id'] = $lang['id'];

      //Если название записи не пустое, то она должна быть активной
      if (!empty($data['name'])) {
        $data['active'] = 1;
      } else {
        $data['active'] = 0;
      }

      if (is_null($id)) {
        if (is_array($data)) {
          foreach ($data as $key => $datum) {
            $data[$key] = preg_replace('/<a[^>]+>([^<]+)<\/a>/i', '$1', $datum);
          }
        }

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

    return $res;
  }

  public function updateEntry($id, Array $data_raw) {

    if (empty($data_raw['common'])) {
      return -1;
    }

    $langs_list = $this->getUserLanguagesList();
    foreach ($langs_list as $lang) {

      if (empty($data_raw[$lang['code']])) {
        continue;
      }

      $data = array_merge($data_raw[$lang['code']], $data_raw['common']);
      $this->_DP_limit_params['languages_id'] = $lang['id'];

      //Если название записи не пустое, то она должна быть активной
      if (!empty($data['name'])) {
        $data['active'] = 1;
      } else {
        $data['active'] = 0;
      }

      if (is_array($data)) {
        foreach ($data as $key => $datum) {
          $data[$key] = preg_replace('/<a[^>]+>([^<]+)<\/a>/i', '$1', $datum);
        }
      }

      $res = parent::updateEntry($id, $data);
    }

    return 1;
  }

  public function getEntry($id) {
    $langs_list = $this->getUserLanguagesList();
    $entry = array();
    foreach ($langs_list as $el) {
      $this->_DP_limit_params['languages_id'] = $el['id'];
      $entry[$el['code']] = parent::getEntry($id);
    }

    return $entry;
  }

  //Управление логотипами и изображениями
  protected function updateImageLogo($type, $id, $width=130, $height=130) {

    $img_res = null;

    //Подготовка, проверка что файл логотипа загружен
    if (isset($_FILES) && isset($_FILES['logo']) && $_FILES['logo']['error']==0) {

      $file_fp = $_FILES['logo']['tmp_name'];

      $entry = $this->getEntry($id);

      if (empty($entry) || !isset($entry['en']['companies_id'])) {
        return $img_res;
      }

      $companies_id = $this->getUserCompanyId();

      $save_as_base = PATH_FRONTEND_DATA_IMAGES . "/companies/";
      $save_as = $companies_id . "/";

      switch ($type) {
        case "employers":
          $save_as .= "employers/";
          break;
        case "services":
          $save_as .= "services/logo/";
          $save_as_big = $save_as;
          break;
        case "news":
          $save_as .= "news/logo/";
          $save_as_big = $save_as;
          break;
        case "galleries":
          $save_as .= "galleries/" . $entry['en']['companies_services_id'] . "/";
          $save_as_big = $save_as;
          break;
        default:
          return $img_res;
      }

      $this->_DP("Filesystem_Images")->createRecursive(array('basePath' => $save_as_base, 'path' => $save_as));

      $save_as = $save_as_base . $save_as . $id . ".jpg";

      $extraParams = array('image_type' => $_FILES['logo']['type']);

      $img_res = $this->_DP("Filesystem_Images")->thumbnailCreate($file_fp, $save_as, $width, $height, $extraParams);
      //Для товаров/сервисов и галлерей сохраняем увеличенную копию
      if (($type == "services" || $type == "galleries" || $type == 'news') && $img_res) {
        $save_as = $save_as_base . $save_as_big . $id . "_big.jpg";
        $this->_DP("Filesystem_Images")->thumbnailCreate($file_fp, $save_as, 640, 480, $extraParams);
      }

      //Для лого товаров/сервисов и новостей сохраняем еще уменьшенное изображение
      if ($img_res && ($type == "services" || $type == "news")) {
        $save_as = $save_as_base . $save_as_big . $id . "_small.jpg";
        $this->_DP("Filesystem_Images")->thumbnailCreate($file_fp, $save_as, 80, 50, $extraParams);
      }
    }

    return $img_res;
  }

}