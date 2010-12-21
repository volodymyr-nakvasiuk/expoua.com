<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_RequestsController extends Sab_Organizer_ControllerAbstract {

	public function viewAction() {
		$this->_view->entry = $this->_model->getEntry($this->_user_param_id);
	}

	public function listAction() {
		parent::listAction();

    if (Zend_Registry::get("language_id") == 1) {
      $this->_view->list_interpret = array(
        "date_add"       => array('title' => 'Дата'),
        "type"           => array(
          'title'      => 'Тип запроса', 
          'substitute' => array(
            'exhibitionExtraInfoRequest' => 'Запросы дополнительной информации по выставке', 
            'exhibitionParticipationRequest' => 'Заказы участия в выставке', 
            'businessTourRequest' => 'Запрос бизнес-тура', 
            'exhibitionCatalogAdvertRequest' => 'Заказы заочного участия', 
            'exhibitionAdvertSpreadRequest' => 'Заказы распространения рекламной продукции на выставке', 
            'serviceCompanyRequest' => 'Запросы к сервисным компаниям', 
            'exhibitionCenterRequest' => 'Запросы к выставочным центрам', 
            'socialOrganizationRequest' => '', 
            'exhibitionRemoteAttendanceRequest' => 'Предварительные заказы заочного посещения выставки', 
            'partnerBusinessTourRequest' => ''
          )
        ),
        "lang_name"      => array('title' => 'Язык запроса'),
        "brand_name"     => array('title' => 'Выставка'),
        "date_from"      => array('title' => 'Дата с'),
        "date_to"        => array('title' => 'Дата по'),
        "purpose2"       => array('title' => 'Цель запроса'),
        "company_name"   => array('title' => 'Компания'),
        "contact_person" => array('title' => 'Контактное лицо'),
        "position"       => array('title' => 'Должность'),
        "city"           => array('title' => 'Город'),
        "phone"          => array('title' => 'Телефон'),
        "fax"            => array('title' => 'Факс'),
        "url"            => array('title' => 'Сайт'),
        "email"          => array('title' => 'E-mail'),
        "address"        => array('title' => 'Адрес'),
        "message"        => array('title' => 'Сообщение'),
        "details"        => array('title' => 'Подробнее'),
        "S1"             => array('title' => 'Площадь 1'),
        "S2"             => array('title' => 'Площадь 2'),
        "S3"             => array('title' => 'Площадь 3'),
        "check1"         => array('title' => 'Выбор 1'),
        "check2"         => array('title' => 'Выбор 2'),
        "check3"         => array('title' => 'Выбор 3'),
        "check4"         => array('title' => 'Выбор 4'),
        "check5"         => array('title' => 'Выбор 5'),
      );
    } else {
      $this->_view->list_interpret = array(
        "date_add"       => array('title' => 'Date'),
        "type"           => array(
          'title'      => 'Request type', 
          'substitute' => array(
            'exhibitionExtraInfoRequest' => 'Additional info request', 
            'exhibitionParticipationRequest' => 'Participation request', 
            'businessTourRequest' => 'Business tour request', 
            'exhibitionCatalogAdvertRequest' => 'Remote participation request', 
            'exhibitionAdvertSpreadRequest' => 'Advertisement request', 
            'serviceCompanyRequest' => 'Request to suppliers', 
            'exhibitionCenterRequest' => 'Request to venues', 
            'socialOrganizationRequest' => '', 
            'exhibitionRemoteAttendanceRequest' => 'Remote participation pre-ordering', 
            'partnerBusinessTourRequest' => ''
          )
        ),
        "lang_name"      => array('title' => 'Language'),
        "brand_name"     => array('title' => 'Trade show'),
        "date_from"      => array('title' => 'From'),
        "date_to"        => array('title' => 'To'),
        "purpose2"       => array('title' => 'Request purpose'),
        "company_name"   => array('title' => 'Company'),
        "contact_person" => array('title' => 'Contact person'),
        "position"       => array('title' => 'Position'),
        "city"           => array('title' => 'City'),
        "phone"          => array('title' => 'Phone'),
        "fax"            => array('title' => 'Fax'),
        "url"            => array('title' => 'Website'),
        "email"          => array('title' => 'Email'),
        "address"        => array('title' => 'Address'),
        "message"        => array('title' => 'Message'),
        "details"        => array('title' => 'Details'),
        "S1"             => array('title' => 'Area 1'),
        "S2"             => array('title' => 'Area 2'),
        "S3"             => array('title' => 'Area 3'),
        "check1"         => array('title' => 'Choice 1'),
        "check2"         => array('title' => 'Choice 2'),
        "check3"         => array('title' => 'Choice 3'),
        "check4"         => array('title' => 'Choice 4'),
        "check5"         => array('title' => 'Choice 5'),
      );
    }
    
		$this->_view->list_brands = $this->_model->getBrandsList();
	}

	public function sendAction() {
		require_once(PATH_VIEWS . "/Frontend/View/Console.php");
		require_once("Zend/Mail.php");
		require_once('Zend/Validate/EmailAddress.php');

		$email = $this->getRequest()->getPost('email');

		$hostValObj = new Zend_Validate_Hostname(Zend_Validate_Hostname::ALLOW_DNS, false);
		$emailValObj = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS, false, $hostValObj);

		if (!$emailValObj->isValid($email)) {
			$this->_setLastActionResult(false);
      	return false;
    	}

    	$mailObj = new Zend_Mail("utf-8");

		$viewObj = new Frontend_View_Console();

		$viewObj->entry = $this->_model->getEntry($this->_user_param_id);

		$params = $this->getRequest()->getUserParams();
		$params['controller'] = $this->getRequest()->getControllerName();
		$params['action'] = 'view';
		$params['simple'] = 1;

		$viewObj->user_params = $params;

		$body = $viewObj->fetch("file:coreSimple.tpl");

		$mailObj->setSubject('User request');
		$mailObj->setFrom("info@expopromoter.com", "EGMS ExpoPromoter.com");

		$mailObj->addTo($email);
		$mailObj->setBodyHtml($body);

		$mailObj->send();

		$this->_setLastActionResult(true);
	}

	//Переопределяем действия для исключения их использования
	public function addAction() {}
	public function editAction() {}
	public function deleteAction() {}
	public function insertAction() {}
	public function updateAction() {}
}