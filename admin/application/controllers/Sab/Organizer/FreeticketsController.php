<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_FreeticketsController extends Sab_Organizer_ControllerAbstract {

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

    if (Zend_Registry::get("language_id") == 1) {
      $this->_view->list_interpret = array(
        "time_created"    => array('title' => 'Время распечатки пригласительного'),
        "date_from"       => array('title' => 'Дата с'),
        "date_to"         => array('title' => 'Дата по'),
        "brand_name"      => array('title' => 'Название выставки'),
        "fname"           => array('title' => 'Имя, отчество'),
        "lname"           => array('title' => 'Фамилия'),
        "positionName"    => array('title' => 'Должность'),
        "email"           => array('title' => 'Персональный e-mail'),
        "companyName"     => array('title' => 'Название компании'),
        "company_phone"   => array('title' => 'Корпоративный телефон'),
        "webAddress"      => array('title' => 'Корпоративный сайт'),
        "company_email"   => array('title' => 'Корпоративный e-mail'),
        "countryName"     => array('title' => 'Страна'),
        "cityName"        => array('title' => 'Город'),
        "postcode"        => array('title' => 'Индекс'),
        "address"         => array('title' => 'Адрес'),
        "comment"         => array('title' => 'Описание компании'),
        "status"          => array(
          'title'      => 'Описание компании',
          'substitute' => array(
            '1'  => 'руководитель/владелец предприятия/организации',
            '2'  => 'менеджер высшего звена/член Совкта директоров',
            '3'  => 'руководитель подразделения/менеджер проекта',
            '4'  => 'техн. специалист',
            '5'  => 'финанс. специалист',
            '6'  => 'специалист по маркетингу/рекламе',
            '7'  => 'специалист по продаже/сбыту',
            '8'  => 'специалист по логистике',
            '9'  => 'специалист по закупкам',
            '10' => 'студент',
            '11' => 'прочее',
            '12' => 'специалист',
          )
        ),

        "functions"    => array(
          'title'      => 'Исполняемые функции',
          'substitute' => array(
            '1'  => 'менеджмент / руководство',
            '2'  => 'проектирование / разработки',
            '3'  => 'информационные системы',
            '4'  => 'образование',
            '5'  => 'реклама / маркетинг',
            '6'  => 'финансы / бухучет',
            '7'  => 'сбыт / продажи',
            '8'  => 'делопроизводство / кадры',
            '9'  => 'производство / технологический процесс',
            '10' => 'техническое обслуживание',
            '11' => 'логистика',
            '12' => 'закупки / снабжение',
            '13' => 'прочее',
            '14' => 'автоматизация производства',
            '15' => 'телекоммуникации / связь',
            '16' => 'безопасность',
          )
        ),
      );
    } else {
      $this->_view->list_interpret = array(
        "time_created"    => array('title' => 'Время распечатки пригласительного'),
        "date_from"       => array('title' => 'Дата с'),
        "date_to"         => array('title' => 'Дата по'),
        "brand_name"      => array('title' => 'Название выставки'),
        "fname"           => array('title' => 'Имя, отчество'),
        "lname"           => array('title' => 'Фамилия'),
        "positionName"    => array('title' => 'Должность'),
        "email"           => array('title' => 'Персональный e-mail'),
        "companyName"     => array('title' => 'Название компании'),
        "company_phone"   => array('title' => 'Корпоративный телефон'),
        "webAddress"      => array('title' => 'Корпоративный сайт'),
        "company_email"   => array('title' => 'Корпоративный e-mail'),
        "countryName"     => array('title' => 'Страна'),
        "cityName"        => array('title' => 'Город'),
        "postcode"        => array('title' => 'Индекс'),
        "address"         => array('title' => 'Адрес'),
        "comment"         => array('title' => 'Описание компании'),
        "status"          => array(
          'title'      => 'Описание компании',
          'substitute' => array(
            '1'  => 'руководитель/владелец предприятия/организации',
            '2'  => 'менеджер высшего звена/член Совкта директоров',
            '3'  => 'руководитель подразделения/менеджер проекта',
            '4'  => 'техн. специалист',
            '5'  => 'финанс. специалист',
            '6'  => 'специалист по маркетингу/рекламе',
            '7'  => 'специалист по продаже/сбыту',
            '8'  => 'специалист по логистике',
            '9'  => 'специалист по закупкам',
            '10' => 'студент',
            '11' => 'прочее',
            '12' => 'специалист',
          )
        ),

        "functions"    => array(
          'title'      => 'Исполняемые функции',
          'substitute' => array(
            '1'  => 'менеджмент / руководство',
            '2'  => 'проектирование / разработки',
            '3'  => 'информационные системы',
            '4'  => 'образование',
            '5'  => 'реклама / маркетинг',
            '6'  => 'финансы / бухучет',
            '7'  => 'сбыт / продажи',
            '8'  => 'делопроизводство / кадры',
            '9'  => 'производство / технологический процесс',
            '10' => 'техническое обслуживание',
            '11' => 'логистика',
            '12' => 'закупки / снабжение',
            '13' => 'прочее',
            '14' => 'автоматизация производства',
            '15' => 'телекоммуникации / связь',
            '16' => 'безопасность',
          )
        ),
      );
    }
  }


	public function viewAction() {
	  $user_id = $this->getRequest()->getUserParam("user", 0);
	  $this->_view->userData = $this->_model->getUserEntry($user_id);
	}


	public function paricipantAction() {
	  
	}


	public function insertAction() {
	}


	public function deleteAction() {
	}

}