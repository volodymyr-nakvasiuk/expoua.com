<?PHP

Zend_Loader::loadClass("Sab_ListControllerAbstract", PATH_CONTROLLERS);

abstract class Sab_Company_ControllerAbstract extends Sab_ListControllerAbstract {

  public $siteRef = null;


  public function init() {
    parent::init();

    // Инициализация сайтового реферала для запоминания с какого сайта был вход
    /*
    $siteRef = $this->getRequest()->getQuery("site", null);
    $siteRef = $this->getRequest()->getUserParam("site", null);

    if ($siteRef) {
      setcookie("sourceSite", $siteRef, time() + 3600*24*30, '/');
      $_COOKIE['sourceSite'] = $siteRef;
    }
    */
    $this->_view->site_ref = $this->getRequest()->getUserParam("site", 'expotop_ru');
    $this->_view->host_site = $GLOBALS['site_url'][$this->_view->site_ref];
  }

  /**
  * Перегружаем функцию инициализации вида
  *
  */
  protected function _initView() {
    Zend_Loader::loadClass("Admin_Company_View", PATH_VIEWS);
    $this->_view = new Admin_Company_View();

    $this->_view->setTemplate("coreCompany.tpl");
  }

  protected function _checkAuth() {
    $userSession = $this->_model->checkUserSession();

    //Если пользователь не зарегистрирован и не находится в модуле аутентификации, направляем его туда
    if ($userSession === false && $this->getRequest()->getControllerName() != 'sab_company_auth') {
      try {
        $this->_helper->redirector->goto('index', 'sab_company_auth', Zend_Registry::get("language_code"), array('site' => $this->getRequest()->getUserParam("site", null)));
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }

    if (!$userSession) {
      return;
    }

    $this->_view->session_user_companies_id = $this->_model->getUserCompanyId();
    $this->_view->session_user = $userSession;
    $this->_view->company_data = $this->_model->getCompanyData();

    //Передаем список разрешенных языков
    $this->_view->list_user_languages = $this->_model->getUserLanguagesList();

    //Информация о локальном языке компании
    if (isset($this->_view->list_user_languages[$this->_view->company_data['language_id']])) {
    	$this->_view->user_language = $this->_view->list_user_languages[$this->_view->company_data['language_id']];
    } else {
    	$this->_view->user_language = $this->_view->list_user_languages[2];
    }
  }

}