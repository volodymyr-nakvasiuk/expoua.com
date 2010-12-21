<?PHP
Zend_Loader::loadClass("Sab_Servcompany_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Servcompany_GalleryController extends Sab_Servcompany_ControllerAbstract {

  public function indexAction() {
    $this->listAction();
  }


  public function listAction() {
    // $this->_model->forceListResults = 1000;

    parent::listAction();
  }


	public function insertAction() {
		parent::insertAction();
	}


	public function deleteAction() {
		parent::deleteAction();
	}

}