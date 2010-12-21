<?PHP

Zend_Loader::loadClass("Sab_Company_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Company_AuthController extends Sab_Company_ControllerAbstract {

	protected function _initView() {
		parent::_initView();

		$this->_view->setTemplate("coreAuthCompany.tpl");
	}

	public function indexAction() {

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

		$this->_checkAuth();

		$this->_setLastActionResult($res);
	}

	public function logoutAction() {
		$this->_model->logout();

		$this->_checkAuth();

		$this->_setLastActionResult(true);
	}

	//Переопределяем действия для исключения их использования
	public function listAction() {}
	public function addAction() {}
	public function editAction() {}
	public function insertAction() {}
	public function deleteAction() {}

}