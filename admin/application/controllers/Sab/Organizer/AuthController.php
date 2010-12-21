<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);
require_once('Zend/Validate/EmailAddress.php');

class Sab_Organizer_AuthController extends Sab_Organizer_ControllerAbstract {

	protected function _initView() {
		parent::_initView();

		$this->_view->setTemplate("coreAuthOrganizer.tpl");
	}

	/**
	 * Аутентификация админа
	 * Функция передает в шаблонизатор код последнего действия:
	 * 1: удача
	 * -1: пользователь с таким логином не существует
	 * -2: More than one record matches the supplied identity (нереально!)
	 * -3: пользователь существует, но пароль не подходит
	 * -4: login и/или пароль пусты
	 *
	 */
	public function loginAction() {
		$data = $this->getRequest()->getPost();

		//Zend_Debug::dump($data);
		if (isset($data['userType'])) {
			$data['login'] = $data['userlogin'];
			$data['passwd'] = $data['pwd'];
		}

		if (empty($data['login']) || empty($data['passwd'])) {
			$res = -4;
		} else {
			$res = $this->_model->auth($data['login'], $data['passwd']);
		}
		
		if ($res == 1 && isset($data['remember'])) {
			setcookie("login", $data['login'], time() + 86400*30, "/");
			setcookie("passwd", md5($data['passwd']), time() + 86400*30, "/");
		}

		$this->_checkAuth();

		$this->_setLastActionResult($res);
	}

	public function logoutAction() {
		$this->_model->logout();
		$this->_checkAuth();
		$this->_setLastActionResult(true);
		
		setcookie("login", "", 0, "/");
		setcookie("passwd", "", 0, "/");

		$this->_helper->redirector->goto('login', 'sab_organizer_auth', null,
			array('language' => Zend_Registry::get("language_code")));
	}

	public function registerAction() {
		$sessObj = new Zend_Session_Namespace();
		$captcha = $sessObj->captcha;
		
		$sessObj->captcha = mt_rand(1000, 9999);
		
		$this->_view->list_countries = $this->_model->getCountriesList();
		if (!$this->getRequest()->isPost()) {
			return;
		}
		
		$data = $this->getRequest()->getPost();
		
		if (!isset($data['captcha']) || $data['captcha'] != $captcha) {
			$this->_setLastActionResult("WRONG_CAPTCHA");
			return;
		}
		
		$hostValObj = new Zend_Validate_Hostname(Zend_Validate_Hostname::ALLOW_DNS, false);
		$emailValObj = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS, false, $hostValObj);

		if (!isset($data['email']) || !$emailValObj->isValid($data['email'])) {
			$this->_setLastActionResult("WRONG_EMAIL");
			return;
		}
		
		if (empty($data['login']) || $this->_model->checkUserLogin($data['login']) == true) {
			$this->_setLastActionResult("LOGIN_EXISTS");
			return;
		}
		
		$res = $this->_model->registerUser($data);

		if ($res == 1) {
			$this->_setLastActionResult("OK");
			
			$dpMailObj = $this->_model->_DP("Email_PagesTemplate");
			
			$dpMailObj->setData($data);
			$dpMailObj->setPageId(122); //id шаблона из admin_cms_pages
			
			$dpMailObj->getMailObj()->setFrom("info@expopromoter.com", "EGMS ExpoPromoter.com");
			$dpMailObj->getMailObj()->addTo($data['email'], $data['name_fio']);
			$dpMailObj->addBcc(
				$this->_model->getConfigValue("EMAIL_REG_NTF_ORGANIZER")
			);
			
			$dpMailObj->send();
		} else {
			$this->_setLastActionResult("FAILED");
		}
	}
	
	protected function _checkAuth() {
		$this->_view->session_user = $this->_model->checkUserSession();
	}
	
	public function indexAction() {
		$this->_helper->redirector->gotoUrl($this->_view->getUrl(array('add' => 1, 'action' => 'login')));
	}
	public function editAction() {
		$this->indexAction();
	}
	public function updateAction() {
		$this->indexAction();
	}
	public function listAction() {
		$this->indexAction();
	}
	public function insertAction() {
		$this->indexAction();
	}
	public function __call($name, $arguments) {
		$this->indexAction();
	}

}