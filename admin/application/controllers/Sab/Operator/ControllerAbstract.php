<?PHP

Zend_Loader::loadClass("Sab_ListControllerAbstract", PATH_CONTROLLERS);

abstract class Sab_Operator_ControllerAbstract extends Sab_ListControllerAbstract {

	/**
	 * Перегружаем функцию инициализации вида
	 *
	 */
	protected function _initView() {
		Zend_Loader::loadClass("Admin_View", PATH_VIEWS);
		$this->_view = new Admin_View();

		$this->_view->setTemplate("coreOperator.tpl");
	}

	protected function _checkAuth() {
		$userSession = $this->_model->checkUserSession();

		//Если пользователь не зарегистрирован и не находится в модуле аутентификации, направляем его туда
		if ($userSession === false && $this->getRequest()->getControllerName() != 'sab_operator_auth') {
			try {
				$this->_helper->redirector->goto('index', 'sab_operator_auth', null,
					array('language' => Zend_Registry::get("language_code")));
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}

		$this->_view->session_user = $userSession;

	}

}