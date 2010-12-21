<?PHP

Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_AuthModel extends Sab_Organizer_ModelAbstract {

	public function auth($user, $passwd) {
		Zend_Loader::loadClass("Zend_Auth_Adapter_DbTable");

		$authAdapterObj = new Zend_Auth_Adapter_DbTable(
			DataproviderAbstract::getDatabaseObjectInstance(),
			"ExpoPromoter_Opt.users_operators",
			"login",
			"passwd",
			"?"
		);

		return $this->_authGeneral($authAdapterObj, $user, $passwd);
	}

	public function logout() {
		Zend_Auth::getInstance()->clearIdentity();
	}
	
	/**
	 * Проверяет существование пользователя с таким логином в базе
	 * Если существует возвращает true, иначе false
	 * @param $login
	 * @return boolean
	 */
	public function checkUserLogin($login) {
		$extraParams = array('login' => $login);
		$res = $this->_DP("List_Operators")->getList(null, null, $extraParams);
		if (empty($res['data'])) {
			return false;
		} else {
			return true;
		}
	}
	
	public function registerUser($data) {
		unset($data['id']);
		//Добавляем неактивным
		$data['active'] = 0;
		$data['super'] = 1;
		$data['type'] = 'organizer';
		$data['user_languages_id'] = self::$_user_language_id;

		return $this->_DP("List_Operators")->insertEntry($data);
	}
	
	public function getCountriesList() {
		$extraParams = array(
			'active' => 1,
			'languages_id' => self::$_user_language_id
		);
		$sort = array("name" => 'ASC');
		return $this->_DP("List_Joined_Ep_Countries")->getList(null, null, $extraParams, $sort);
	}

}