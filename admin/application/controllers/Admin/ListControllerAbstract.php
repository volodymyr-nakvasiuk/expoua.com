<?PHP

Zend_Loader::loadClass("Admin_ControllerAbstract", PATH_CONTROLLERS);

abstract class Admin_ListControllerAbstract extends Admin_ControllerAbstract {

	public function indexAction() {
	}

	/**
	 * Элементарное действие вывода списка
	 *
	 */
	public function listAction() {
		$search = $this->_prepareSearch();

		$results = $this->getRequest()->getUserParam("results", null);

		if (!is_null($results)) {
			$this->_model->forceListResults = intval($results);
		}

		$this->_view->list = $this->_model->getList($this->_user_page, $this->_user_param_parent, $this->_user_param_sort, $search);
	}

	/**
	 * Элементарное действие редактирования записи
	 *
	 */
	public function editAction() {
		$this->_view->entry = $this->_model->getEntry($this->_user_param_id);
	}

	/**
	 * Элементарное действие при добавлении записи
	 *
	 */
	public function addAction() {
		//Ничего не делаем - не известно какие данные потребуются при добавлении
	}

	/**
	 * Элементарное действие обновления записи
	 *
	 */
	public function updateAction() {

		$data = $this->getRequest()->getPost();

		if (empty($data)) {
			$this->_setLastActionResult(-3);
			return;
		}

		if (!isset($data['parent_id'])) {
			$data['parent_id'] = $this->_user_param_parent;
		}

		if (is_numeric($this->_user_param_id)) {
			$res = $this->_model->updateEntry($this->_user_param_id, $data);
		} else {
			$res = -2;
		}

		$this->_setLastActionResult($res);
	}

	/**
	 * Элементарное действие удаления записи
	 *
	 */
	public function deleteAction() {
		if (is_numeric($this->_user_param_id)) {
			$res = $this->_model->deleteEntry($this->_user_param_id);
		} else {
			$res = -2;
		}

		$this->_setLastActionResult($res);
	}

	/**
	 * Элементарное действие добавления записи
	 *
	 */
	public function insertAction() {
		$data = $this->getRequest()->getPost();

		if (empty($data)) {
			$this->_setLastActionResult(-3);
			return;
		}

		if (!isset($data['parent_id'])) {
			$data['parent_id'] = $this->_user_param_parent;
		}

		//if (isset($_GET['debug'])) Zend_Debug::dump($data);

		$copy_all_langs = false;
		//Проверяем, установлен ли флаг "копировать во все языковые версии"
		if (isset($data['_shelby_copy_all_langs']) && $data['_shelby_copy_all_langs']==1) {
			$copy_all_langs = true;
			unset($data['_shelby_copy_all_langs']);
		}

		$res = $this->_model->insertEntry($data, $copy_all_langs);

		$this->_setLastActionResult($res);
	}

	/**
	 * Элементарное действие просмотра записи
	 *
	 */
	public function viewAction() {
		$this->_view->entry = $this->_model->getEntry($this->_user_param_id);
	}

}