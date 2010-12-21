<?PHP

/**
* Абстрактный класс, реализующий основную списковую функциональность для укороченной системы администрирования
* Sab - Simple Admin Base
*
*/
abstract class Sab_ListControllerAbstract extends ControllerAbstract {

	/**
	 * Перегружаем функцию инициализации вида
	 *
	 */
	protected function _initView() {

		$responseType = $this->getRequest()->getUserParam("feed", null);

		switch ($responseType) {
			case "csv":
				Zend_Loader::loadClass("Frontend_Csv", PATH_VIEWS);
				$this->_view = new Frontend_Csv();
				break;
			case "html":
			default:
        Zend_Loader::loadClass("Frontend_View", PATH_VIEWS);
        $this->_view = new Frontend_View();
		}

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

    $this->_view->list = $this->_model->getList($this->_user_page, $this->_user_param_sort, $search);
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

    if (is_numeric($this->_user_param_id)) {
      $res = $this->_model->updateEntry($this->_user_param_id, $data);
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

    $res = $this->_model->insertEntry($data, true);

    $this->_setLastActionResult($res);
  }

  public function deleteAction() {
    if (is_numeric($this->_user_param_id)) {
      $res = $this->_model->deleteEntry($this->_user_param_id);
    } else {
      $res = -2;
    }

    $this->_setLastActionResult($res);
  }

}