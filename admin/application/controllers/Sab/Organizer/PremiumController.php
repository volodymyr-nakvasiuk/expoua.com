<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_PremiumController extends Sab_Organizer_ControllerAbstract {

	protected function _initView() {
		parent::_initView();

		$this->_view->setTemplate("coreSimple.tpl");
	}

	public function addAction() {
		if (!is_null($this->_user_param_parent)) {
			$this->_view->entry_event = $this->_model->getEventEntry($this->_user_param_parent);
		}

		$this->_view->user_session_email = $this->_model->_user_session->operator->email;
	}

	public function insertAction() {
		$data = $this->getRequest()->getPost();
		$entry_event = $this->_model->getEventEntry($this->_user_param_parent);

		if (empty($data)) {
			$this->_forward("add");
		}

		require_once("Zend/Mail.php");
		$mailObj = new Zend_Mail("utf-8");

		$message = '<html><body><h4>Поступил заказ премиум-пакета:</h4><br>
Выставка: ' . $entry_event['brand_name'] . ' (' . $entry_event['date_from'] . ') id: ' . $entry_event['id'] . '<br>
Контактный email: ' . $data['email'] . '<br>
Логин организатора: ' . $this->_model->checkUserSession() . '<br>
ФИО: ' . $this->_model->_user_session->operator->name_fio .'<br>
Должность: ' . $this->_model->_user_session->operator->position . '<br>
Комментарии: ' . nl2br($data['message']) . '<br>
</body></html>';

		$mailObj->setSubject('Заказ премиум-пакета');
		$mailObj->setFrom("info@expopromoter.com", "EGMS ExpoPromoter.com");

		$mailObj->addTo("mariya.shtanhrat@expopromoter.com");
		$mailObj->addTo("alla.kostenko@expopromoter.com");
		$mailObj->addTo("gennadiy.netyaga@expopromoter.com");
		$mailObj->setBodyHtml($message);
		$mailObj->send();

		$this->_setLastActionResult(1);
	}

	//Переопределяем действия для исключения их использования
	public function listAction() {}
	public function editAction() {}
	public function updateAction() {}
	public function deleteAction() {}
}