<?PHP
require_once("Zend/Mail.php");
require_once('Zend/Validate/EmailAddress.php');
require_once(PATH_VIEWS . "/Frontend/View/Console.php");

class Sab_Company_RegisterController extends ControllerAbstract {

  protected function _initView() {
    Zend_Loader::loadClass("Admin_View", PATH_VIEWS);
    $this->_view = new Admin_View();

    $this->_view->setTemplate("coreRegisterCompany.tpl");
  }

  public function indexAction() {
  }

  public function proceedAction() {
    $data = $this->getRequest()->getPost();

    if (empty($data)) {
      $this->_helper->redirector->goto('index', 'sab_company_register', $this->_view->selected_language);
    }

    $res = $this->_model->registerCompany($data);
    $this->_setLastActionResult($res);

    if ($res == 1) {
      $this->_sendConfirmationEmail();
    }
  }

  private function _sendConfirmationEmail() {
    $hostValObj = new Zend_Validate_Hostname(Zend_Validate_Hostname::ALLOW_DNS, false);
    $emailValObj = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS, false, $hostValObj);

    $company = $this->_model->getCompanyData();
    $user = $this->_model->getCompanyUserData();

    //Zend_Debug::dump($company);
    //Zend_Debug::dump($user);

    if (!$emailValObj->isValid($user['email'])) {
      return false;
    }

    $mailObj = new Zend_Mail("utf-8");
    $view = new Frontend_View_Console();

    //$page_data = $this->_DP("List_Joined_Pages")->getEntry(36);
    //Zend_Debug::dump($page_data);

    $view->assign("_system_include_conent", "contents/" . 36);
    $view->assign("user", $user);
    $view->assign("company", $company);

    $message = $view->fetch("system/email_base");

    $mailObj->setSubject('Company registration');
    $mailObj->setFrom("info@expopromoter.com", "EGMS ExpoPromoter.com");

    $mailObj->addTo($user['email'], $user['name']);
    //$mailObj->addBcc('dmitry.sinev@expopromogroup.com');

    $mailObj->setBodyHtml($message);

    $res = $mailObj->send();

    //echo "Mail sended...";

    return $res;
  }

}