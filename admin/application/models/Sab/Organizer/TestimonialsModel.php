<?PHP

require_once(PATH_MODELS . "/Sab/Organizer/ModelAbstract.php");

class Sab_Organizer_TestimonialsModel extends Sab_Organizer_ModelAbstract {

  public function sendTestimonial($data) {
    require_once("Zend/Mail.php");
    require_once(PATH_VIEWS . "/Frontend/View/Console.php");

    $lang_id = 1;
    $tpl_code = 115;

    $tpl_data = $this->_DP('List_Joined_Pages')->getEntry($tpl_code, array('languages_id' => $lang_id));

    $old_lang = Zend_Registry::get("language_id");
    Zend_Registry::set("language_id", $lang_id);
    
    $mailObj = new Zend_Mail("utf-8");
    $view = new Frontend_View_Console();

    $view->assign("_system_include_conent", "contents/$tpl_code");

    $view->assign("data", $data);
    
    $organizer_data = $this->_DP('List_Joined_Ep_Organizers')->getEntry($this->_user_session->organizer['id'], array('languages_id' => $lang_id));
    
    $view->assign("organizer", $organizer_data);

    $message = $view->fetch("system/email_base");

    Zend_Registry::set("language_id", $old_lang);

    $mailObj->setSubject($tpl_data['title']);
    $mailObj->setFrom("info@expopromoter.com", "ExpoPromoter");

    $mailObj->addTo('gn@expopromoter.com');
    $mailObj->addBcc('mariya.shtanhrat@expopromogroup.com');
    $mailObj->addBcc('anastasiya.shumak@expopromogroup.com');
    $mailObj->addBcc('eugene.ivashin@expopromogroup.com');

    $mailObj->setBodyHtml($message);

    $mailObj->send();

    return 1;
  }

}