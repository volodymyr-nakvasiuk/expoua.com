<?php

Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_StatController extends Sab_Organizer_ControllerAbstract {

  public function listAction() {
    if (empty($this->_user_param_sort)) {
      $this->_user_param_sort = array('date_from' => 'DESC');
    }

    $mode = $this->getRequest()->getUserParam("mode");
    $this->_view->mode = $mode;

    if ($mode == 'details') {
      $this->_model->detailed = true;
    }
    
    $this->_model->forceListResults = 99999;

    parent::listAction();

    if (Zend_Registry::get("language_id") == 1) {
      $this->_view->list_interpret = array(
        "name"            => array('title' => 'Выставка'),
        "date_from"       => array('title' => 'Дата с'),
        "date_to"         => array('title' => 'Дата по'),
        "active"          => array(
          'title'      => 'Активность', 
          'substitute' => array(
            '0' => 'Нет', 
            '1' => 'Да'
          )
        ),
        "city_name"       => array('title' => 'Город'),
        "country_name"    => array('title' => 'Страна'),
        "organizer_name"  => array('title' => 'Организатор'),
        "expocenter_name" => array('title' => 'Экспоцентр'),

        "view_cnt"        => array('title' => 'Просмотры'),
        "req_cnt"         => array('title' => 'Запросы'),
        "redir_cnt"       => array('title' => 'Переходы на сайт'),
        "galleries"       => array('title' => 'Фото в галерее'),
      );
    } else {
      $this->_view->list_interpret = array(
        "name"            => array('title' => 'Trade show'),
        "date_from"       => array('title' => 'From'),
        "date_to"         => array('title' => 'To'),
        "active"          => array(
          'title'      => 'Activity', 
          'substitute' => array(
            '0' => 'No', 
            '1' => 'Yes'
          )
        ),
        "city_name"       => array('title' => 'City'),
        "country_name"    => array('title' => 'Country'),
        "organizer_name"  => array('title' => 'Organizer'),
        "expocenter_name" => array('title' => 'Venue'),

        "view_cnt"        => array('title' => 'Views'),
        "req_cnt"         => array('title' => 'Requests'),
        "redir_cnt"       => array('title' => 'Site redirects'),
        "galleries"       => array('title' => 'Photos'),
      );
    }
  }


  public function viewAction() {
    $mode = $this->getRequest()->getUserParam("mode", 'Hits');
    
    $this->_view->mode = $mode;
    
    $this->_view->event = $this->_model->getEntry($this->_user_param_id);
    
    $results = $this->getRequest()->getUserParam("results", $this->_model->getConfigValue("GENERAL_ELEMENTS_PER_PAGE"));
    $this->_model->forceListResults = $results;

    if ($mode == 'Hits') {
      $this->_view->list = $this->_model->getStatList($this->_user_param_id, $this->_user_page);
    } else if ($mode == 'Redirects') {
      $this->_view->list = $this->_model->getRedirectStatList($this->_user_param_id, $this->_user_page);
    } else {
    
    }
  }


  //Переопределяем действия для исключения их использования

  public function addAction() {}
  public function editAction() {}
  public function insertAction() {}
  public function updateAction() {}
  public function deleteAction() {}

}