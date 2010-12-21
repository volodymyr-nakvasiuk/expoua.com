<?PHP

Zend_Loader::loadClass("Admin_ListControllerAbstract", PATH_CONTROLLERS);

class Admin_Ep_EventsController extends Admin_ListControllerAbstract {

	public function editAction() {
		parent::editAction();

		$this->_view->list_periods = $this->_model->getPeriodsList();
		$this->_view->list_requests = $this->_model->getRequestsList();
	}

	public function addAction() {
		parent::addAction();

		$this->_view->list_periods = $this->_model->getPeriodsList();
		$this->_view->list_requests = $this->_model->getRequestsList();
	}

	public function copyAction() {
		$this->_view->entry = $this->_model->getAllLangsEntry($this->_user_param_id);

		$this->_view->list_periods = $this->_model->getPeriodsList();
	}

	/**
	 * Переопределяем функцию добавления записи - вводим проверку на флаг,
	 * указывающий, что идут данные для всех языков сразу
	 *
	 */
	public function insertAction() {
		if (is_null($this->getRequest()->getPost("_shelby_insert_all_langs", null))) {
			parent::insertAction();
		} else {
			$data = $this->getRequest()->getPost();
			unset($data['_shelby_insert_all_langs']);
			$res = $this->_model->insertAllLangsEntry($data);

			$this->_setLastActionResult($res);
		}
	}

}