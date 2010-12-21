<?PHP

Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_SelfModel extends Sab_Organizer_ModelAbstract {

  protected $_DP_name = 'List_Joined_Ep_Organizers';

  public function getEntry($id) {
    $id = $this->getUserOrganizerId();

    $res = $this->_DP_obj->getEntry($id, array('languages_id' => self::$_user_language_id));

    $res['socorgs'] = $this->_DP_obj->getSelectedSocOrgsList($id);

    return $res;
  }


  /**
  * Обновляет указанную в первом параметре запись
  * Данные для обновления передаются во втором параметре
  * Функция возвращает количество фактически обновленных записей
  *
  * @param int $id
  * @param array $data
  * @return int
  */
  public function updateEntry($id, Array $data) {
    $id = $this->getUserOrganizerId();

    $entry = $this->_DP_obj->getEntry($id);

    if (
      (isset($data['name']) && $data['name'] != $entry['name']) ||
      (isset($data['description']) && $data['description'] != $entry['description'])
    ) {
      $this->_sendNotification($id, $data, 112);
    }


    $this->_DP_limit_params['languages_id'] = self::$_user_language_id;
    $res = $this->_DP_obj->updateEntry($id, $data, $this->_DP_limit_params);

    if (empty($data['so'])) {
      $data['so'] = array();
    }

    $this->_DP_obj->updateSocOrgs($id, $data['so']);

    return 1;
  }


  public function getSocialOrgsList() {
    $list = $this->_DP("List_Joined_Ep_Socorgs")->getList(null, null, array('languages_id' => self::$_user_language_id), array("name" => "ASC"));

    return $list['data'];
  }


  private function _sendNotification($id, $data, $tpl_code = 112) {
    require_once("Zend/Mail.php");
    require_once('Zend/Validate/EmailAddress.php');
    require_once(PATH_VIEWS . "/Frontend/View/Console.php");

    $organizer_data = $this->_DP_obj->getEntry($id);

    // $hostValObj = new Zend_Validate_Hostname(Zend_Validate_Hostname::ALLOW_DNS, false);
    // $emailValObj = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS, false, $hostValObj);

    // $email = '';
    // if (!$emailValObj->isValid($email)) return false;

    $lang_id = 1;
    $tpl_data = $this->_DP('List_Joined_Pages')->getEntry($tpl_code, array('languages_id' => $lang_id));

    $mailObj = new Zend_Mail("utf-8");
    $view = new Frontend_View_Console();

    $view->assign("_system_include_conent", "contents/$tpl_code");

    $view->assign("organizer", $organizer_data);

    $message = $view->fetch("system/email_base", $lang_id);

    $mailObj->setSubject($tpl_data['title']);
    $mailObj->setFrom("info@expopromoter.com", "ExpoPromoter Service");

    // $mailObj->addTo($email, !empty($pbluser_data['name']) ? $pbluser_data['name'] : $pbluser_data['login']);
    $mailObj->addto('gn@expopromogroup.com', 'Геннадий Нетяга');
    $mailObj->addBcc('mariya.shtanhrat@expopromogroup.com', 'Мария Штанграт');
    $mailObj->addBcc('anastasiya.shumak@expopromogroup.com', 'Анастасия Шумак');
    $mailObj->addBcc('anna.shalya@expopromogroup.com', 'Анна Шаля');
    $mailObj->addBcc('eugene.ivashin@expopromogroup.com', 'Евгений Ивашин');

    $mailObj->setBodyHtml($message);

    $mailObj->send();

    return true;
  }

}
