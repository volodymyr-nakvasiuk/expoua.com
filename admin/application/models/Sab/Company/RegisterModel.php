<?PHP

class Sab_Company_RegisterModel extends ModelAbstract {

  private $_company_id = null;
  private $_company_user_id = null;

  public function registerCompany($data) {

    /*if (!empty($data['companies_id'])) {
      $company_test = $this->_DP("List_Joined_Ep_Companies")->getEntry($data['companies_id'], array('name' => $data['name']));

      if (empty($company_test)) {
        $companies_id = $this->_addCompany($data);
      } else {
        $companies_id = $company_test['id'];
      }
    } else {
      $companies_id = $this->_addCompany($data);
    }*/

    $companies_id = $this->_addCompany($data);

    $this->_company_id = $companies_id;

    $user_data = array('login' => $data['login'], 'passwd' => $data['passwd'], 'name' => $data['contact_person'],
            'email' => $data['email'], 'companies_id' => $companies_id, 'active' => 1,
            'languages_id' => $data['local_languages_id']);

    $res = $this->_addUser($user_data);

    return $res;
  }

  public function getCompanyData() {
    return $this->_DP("List_Joined_Ep_Companies")->getEntry($this->_company_id);
  }

  public function getCompanyUserData() {
    return $this->_DP("List_Users_Companies")->getEntry($this->_company_user_id);
  }

  /**
  * Добавляет нового пользователя компаний
  * Возвращает 1 в случае успешного добавления
  *
  * @param array $data
  * @return int
  */
  private function _addUser(Array $data) {

    $res = $this->_DP("List_Users_Companies")->insertEntry($data);

    $this->_company_user_id = $this->_DP("List_Users_Companies")->getLastInsertId();

    return $res;
  }

  /**
  * Добавляет новую компанию
  * Возвращает id добавленной записи
  *
  * @param array $data
  * @return int
  */
  private function _addCompany(Array $data) {

    $DP = $this->_DP("List_Joined_Ep_Companies");

    //Добавляем неактивной
    $data['active'] = 0;

    //В английскую версию
    $data['languages_id'] = 2;

    $res = $DP->insertEntry($data);
    $id = $DP->getLastInsertId();

    //Если локальный язык выбран не английский, то создаем языковую версию для выбранного языка
    if ($data['local_languages_id'] != 2 && $res) {
      $data['id'] = $id;
      $data['languages_id'] = $data['local_languages_id'];
      $DP->insertLanguageData($data);
    }

    return $id;
  }

}