<?PHP

Zend_Loader::loadClass("Admin_ControllerAbstract", PATH_CONTROLLERS);

class Admin_AuthController extends Admin_ControllerAbstract {

	protected function _initView() {
		parent::_initView();

		$this->_view->setTemplate("coreAuth.tpl");
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

}