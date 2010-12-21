<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_SelfController extends Sab_Organizer_ControllerAbstract {

  /**
  * передаем данные пользователя в шаблон формы редактирования
  */
  public function indexAction() {
    $this->editAction();
  }

  /**
  * передаем данные пользователя в шаблон формы редактирования
  */
  public function editAction() {
    $this->_view->entry = $this->_model->getEntry(0);

    $this->_view->list_socorgs = $this->_model->getSocialOrgsList();
  }

  /**
  *
  */
  public function updateAction() {
    $data = $this->getRequest()->getPost();

    if (empty($data)) {
      $this->_setLastActionResult(-3);
      return;
    }

    $res = $this->_model->updateEntry(0, $data);
    $this->_setLastActionResult($res);
  }

}