<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_GalleryController extends Sab_Organizer_ControllerAbstract {

  public function indexAction() {
    $this->_view->list = $this->_model->getEventsList(null, null, null);
  }


  public function listAction() {
		if (!empty($this->_user_param_parent)) {
		  $this->_model->_event_id = $this->_user_param_parent;
      $this->_view->parent_id  = $this->_user_param_parent;
		  
		  $this->_view->event = $this->_model->getEventEntry($this->_model->_event_id);
			$this->_view->entry_event = $this->_model->getList(null, null, null);
		} else {
			$this->_setLastActionResult(-3);
			return;
		}

    if (empty($this->_user_param_sort)) {
      $this->_user_param_sort = array('date_from' => 'ASC');
    }

    $this->_model->forceListResults = 1000;

    parent::listAction();
  }


	public function insertAction() {
		if (empty($this->_user_param_parent)) {
			$this->_setLastActionResult(-3);
			return;
		}
		
		// Zend_Debug::dump($this->_user_param_parent);

		parent::insertAction();
	}


	public function deleteAction() {
		if (empty($this->_user_param_parent)) {
			$this->_setLastActionResult(-3);
			return;
		}

    $this->_model->_event_id = $this->_user_param_parent;

		parent::deleteAction();
	}

}