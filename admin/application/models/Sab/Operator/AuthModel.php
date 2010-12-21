<?PHP

Zend_Loader::loadClass("Sab_Operator_ModelAbstract", PATH_MODELS);

class Sab_Operator_AuthModel extends Sab_Operator_ModelAbstract {

	public function auth($user, $passwd) {
		Zend_Loader::loadClass("Zend_Auth_Adapter_DbTable");

		$authAdapterObj = new Zend_Auth_Adapter_DbTable(
			DataproviderAbstract::getDatabaseObjectInstance(),
			"ExpoPromoter_Opt.users_operators",
			"login",
			"passwd",
			"?"
		);

		$authAdapterObj->setIdentity($user);
		$authAdapterObj->setCredential($passwd);

		$result = Zend_Auth::getInstance()->authenticate($authAdapterObj);

		$row = $authAdapterObj->getResultRowObject();

		if ($result->getCode() === 1 && $row->active == 1 && $row->type == 'operator') {
			$this->_user_session->operator = $row;
		} else {
			Zend_Auth::getInstance()->clearIdentity();
			return Zend_Auth_Result::FAILURE;
		}

		return $result->getCode();
	}

	public function logout() {
		Zend_Auth::getInstance()->clearIdentity();
	}

}