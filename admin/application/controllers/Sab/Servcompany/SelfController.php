<?php
Zend_Loader::loadClass("Sab_Servcompany_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Servcompany_SelfController extends Sab_Servcompany_ControllerAbstract {
  
  public function editAction() {
    parent::editAction();

    $this->_view->list_service_companies_cats = $this->_model->getCategoriesList(); 
  }


  public function updateAction() {
    $data = $this->getRequest()->getPost();

    if (empty($data)) {
      $this->_setLastActionResult(-3);
      return;
    }
    
    $res = $this->_model->updateEntry($this->_user_param_id, $data);
    $this->_setLastActionResult($res);
  }

}