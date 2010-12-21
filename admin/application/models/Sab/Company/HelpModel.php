<?PHP

Zend_Loader::loadClass("Sab_Company_ModelAbstract", PATH_MODELS);

class Sab_Company_HelpModel extends Sab_Company_ModelAbstract {

	public function sendMessage($data) {
		require_once("Zend/Mail.php");

		$mailObj = new Zend_Mail("utf-8");
		$mailObj->addTo("eugene.ivashin@expopromogroup.com");
		$mailObj->setFrom($data['email'], $data['name']);
		$mailObj->setSubject("Сообщение от компании из админки");

		$userData = $this->_DP("List_Users_Companies")->getEntry($this->_user_session->companies->id);

		$message = 'Контактное лицо: ' . $data['name'] . "\n" .
		"Email: " . $data['email'] . "\n".
		"Сообщение:\n" . $data['message'] . "\nИнформация о пользователе:\n" . print_r($userData, true);

		$mailObj->setBodyText($message);

		$mailObj->send();
	}

}