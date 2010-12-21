<?php

Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_BrandseventsController extends Sab_Organizer_ControllerAbstract {

  public function listAction() {

    if (empty($this->_user_param_sort)) {
      $this->_user_param_sort = array('date_from' => 'ASC');
    }

    $this->_model->forceListResults = 999;

    parent::listAction();

    $this->_view->list_brands_marked = $this->_model->getDistinctDraftsBrandsList();
    $this->_view->list_drafts = $this->_model->getDraftsList();
  }

  public function editAction() {
    parent::editAction();

    $this->_view->list_periods = $this->_model->getPeriodsList();
    $this->_view->list_brand_categories = $this->_model->getBrandCategoriesList();
  }

  //Переопределяем действия для исключения их использования
  public function addAction() {}
  public function insertAction() {}
  public function deleteAction() {}

}