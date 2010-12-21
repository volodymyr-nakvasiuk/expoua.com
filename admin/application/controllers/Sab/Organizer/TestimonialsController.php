<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_TestimonialsController extends Sab_Organizer_ControllerAbstract {

  //Переопределяем действия для исключения их использования
  public function addAction() {}
  public function editAction() {}
  public function updateAction() {}
  public function deleteAction() {}

  public function indexAction() {
    $data = $this->getRequest()->getPost();

    if (empty($data)) {
      $this->_setLastActionResult(-3);
      return;
    }

    $res = $this->_model->sendTestimonial($data);

    $this->_setLastActionResult($res);
  }
}