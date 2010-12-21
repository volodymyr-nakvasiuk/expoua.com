<?PHP

Zend_Loader::loadClass("Admin_ModelAbstract", PATH_MODELS);

class Admin_AuthModel extends Admin_ModelAbstract {

	public function auth($user, $passwd) {
		Zend_Loader::loadClass("Zend_Auth_Adapter_DbTable");

		$authAdapterObj = new Zend_Auth_Adapter_DbTable(
			DataproviderAbstract::getDatabaseObjectInstance(),
			"acl_admin_users",
			"login",
			"passwd",
			"MD5(?)"
		);

		$authAdapterObj->setIdentity($user);
		$authAdapterObj->setCredential($passwd);

		//$result = $authAdapterObj->authenticate();
		$result = Zend_Auth::getInstance()->authenticate($authAdapterObj);

		$row = $authAdapterObj->getResultRowObject();

		//Zend_Debug::dump($row);

		//Если пользователь залогинился успешно, обновляем время последнего входа
		if ($result->getCode() === 1) {
			//Пускаем только если пользователь активен
			if ($row->active == 1) {
				$this->_DP("List_AclAdmins")->updateEntry(null, array('time_lastlogin' => date("Y-m-d H:i:s")), array('login' => $user));
			} else {
				Zend_Auth::getInstance()->clearIdentity();
				return $result->getCode();
			}
		}

		//Zend_Debug::dump();
		return $result->getCode();
	}

	public function logout() {
		Zend_Auth::getInstance()->clearIdentity();
	}

}