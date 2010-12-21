<?PHP
require_once(PATH_MODELS . "/Admin/ListModelAbstract.php");

class Admin_Ep_Pblbanners_BannersModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Banners_PblBanners';

	public function updateEntry($id, Array $data) {
		$this->_DP_limit_params = array();

		$res = parent::updateEntry($id, $data);

		if (isset($data['active'])) {
		  if ($data['active'] == 1) {
		    $this->_sendNotification($id, $data, 106);
		  } else {
		    $this->_sendNotification($id, $data, 109);
		  }
		}

		//Обновляем материализованное представление
		$this->_DP_obj->updateBannerMView($id);

		return $res;
	}


	public function getList($page = null, $parent = -1, $sort = null, $search = null) {
		$this->_DP_limit_params = array('deleted' => 0, '_check_banners_presence' => true);

		return parent::getList($page, $parent, $sort, $search);
  }


	public function deleteEntry($id) {
		return 0;
	}


  private function _sendNotification($id, $data, $tpl_code = 106) {
    require_once("Zend/Mail.php");
    require_once('Zend/Validate/EmailAddress.php');
    require_once(PATH_VIEWS . "/Frontend/View/Console.php");

    $pblbanner_data = $this->_DP("List_Banners_PblBanners")->getEntry($id);
    $pbluser_data = $this->_DP("List_Banners_PblUsers")->getEntry($pblbanner_data['users_id']);

    $hostValObj = new Zend_Validate_Hostname(Zend_Validate_Hostname::ALLOW_DNS, false);
    $emailValObj = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS, false, $hostValObj);

    $email = $pbluser_data['email'];

    if (!$emailValObj->isValid($email)) return false;

    $lang_id = in_array($pbluser_data['countries_id'], array(13, 17, 26, 33, 34, 39, 45, 52, 60, 154, 162, 166, 185, 189, 190)) ? 1 : 2;

    $tpl_data = $this->_DP('List_Joined_Pages')->getEntry($tpl_code, array('languages_id' => $lang_id));

    $old_lang = Zend_Registry::get("language_id");
    Zend_Registry::set("language_id", $lang_id);
    
    $mailObj = new Zend_Mail("utf-8");
    $view = new Frontend_View_Console();

    $view->assign("_system_include_conent", "contents/$tpl_code");

    $view->assign("banner", $pblbanner_data);
    $view->assign("user", $pbluser_data);

    $message = $view->fetch("system/email_base");

    Zend_Registry::set("language_id", $old_lang);

    $mailObj->setSubject($tpl_data['title']);
    $mailObj->setFrom("advert@expopromoter.com", "ExpoAdvert Advertisement Network");

    $mailObj->addTo($email, !empty($pbluser_data['name']) ? $pbluser_data['name'] : $pbluser_data['login']);
    $mailObj->addBcc('advert@expopromoter.com');
    $mailObj->addBcc('eugene.ivashin@expopromogroup.com');

    $mailObj->setBodyHtml($message);

    $mailObj->send();

/*
    mail('eugene.ivashin@expopromogroup.com', 'PblAdvertiser Debug', "Template: " . $tpl_data['title'] . "\n\n" . "Lang code: " . $lang_id . "\n\n" . print_r($pbluser_data, true) . "\n\n" . print_r($tpl_data, true));

*/
    return true;
  }

}