<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_NewsController extends Sab_Organizer_ControllerAbstract {

  public function addAction() {
    parent::addAction();
    $this->_view->list_brand_categories = $this->_model->getBrandCategoriesList();
    $this->_view->list_countries = $this->_model->getCountriesList();
  }

  public function previewAction() {
    $this->_view->setTemplate('controllers_frontend/sab_organizer_news/preview.tpl');

    if ($this->_user_param_id) {
      $data = $this->getEntry($this->_user_param_id);
    } else {
      $data = $this->getRequest()->getPost();
    }

    $this->_view->data = $data;
  }

  //Переопределяем действия для исключения их использования
  public function editAction() {}
  public function updateAction() {}
  public function deleteAction() {}
}