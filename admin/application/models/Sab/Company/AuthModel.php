<?PHP

Zend_Loader::loadClass("Sab_Company_ModelAbstract", PATH_MODELS);

class Sab_Company_AuthModel extends Sab_Company_ModelAbstract {

	public function confirm($code) {
	  $this->_DP("List_Users_Companies")->getEntry(null, array('companies_id' => $id));
    if (1) ;
  }



	public function auth($user, $passwd) {
		Zend_Loader::loadClass("Zend_Auth_Adapter_DbTable");

		$authAdapterObj = new Zend_Auth_Adapter_DbTable(
			DataproviderAbstract::getDatabaseObjectInstance(),
			"ExpoPromoter_Opt.users_sites",
			"login",
			"passwd",
			"?"
		);

		$authAdapterObj->setIdentity($user);
		$authAdapterObj->setCredential($passwd);

		$result = Zend_Auth::getInstance()->authenticate($authAdapterObj);

		$row = $authAdapterObj->getResultRowObject();
		
		if ($result->getCode() === 1 && $row->active == 1) {
			
			//Если у пользователя не создана компания, создаем ее
			if (is_null($row->companies_id)) {
				$row->companies_id = $this->_addCompany($row);
			}
			
			$company_data = $this->_DP("List_Joined_Ep_Companies")->getEntry($row->companies_id);

			$row->local_languages_id = $company_data['local_languages_id'];

			$this->_user_session->companies = $row;
			$this->_user_session->company_data = array(
				'id' => $company_data['id'],
				'active' => $company_data['active'],
				'name' => $company_data['name'],
				'language_id' => $company_data['local_languages_id']
			);
		} else {
			Zend_Auth::getInstance()->clearIdentity();
			//return Zend_Auth_Result::FAILURE;
		}
		
		return $result->getCode();
	}

	public function logout() {
		Zend_Auth::getInstance()->clearIdentity();
	}

	/**
	 * Функция создания компании на основе регистрационных данных пользователя
	 * 
	 * @param stdClass $row
	 */
	private function _addCompany(stdClass $row) {

		$DP = $this->_DP("List_Joined_Ep_Companies");

		//Добавляем неактивной
		$data = array(
			'active' => 0,
			'email' => $row->email,
			'local_languages_id' => $row->languages_id,
			'name' => $row->text_comp);

		//В английскую версию
		$data['languages_id'] = 2;

		$res = $DP->insertEntry($data);
		$id = $DP->getLastInsertId();
		
		//обновляем информацию о пользователе. Теперь у него есть компания!
		$this->_DP("List_Users_Companies")->updateEntry($row->id, array('companies_id' => $id));

		//Если локальный язык выбран не английский, то создаем языковую версию для выбранного языка
		if ($data['local_languages_id'] != 2 && $res) {
			$data['id'] = $id;
			$data['languages_id'] = $data['local_languages_id'];
			$DP->insertLanguageData($data);
		}

		return $id;
	}
}