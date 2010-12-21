<?PHP

Zend_Loader::loadClass("Sab_Servcompany_ModelAbstract", PATH_MODELS);

class Sab_Servcompany_SelfModel extends Sab_Servcompany_ModelAbstract {

	public function getEntry($id)
	{
		$langs_list = $this->_DP("List_Languages")->getList();
   		
		foreach ($langs_list['data'] as $lang)
		{
			$res[$lang['code']] = $this->_DP("List_Joined_Ep_ServiceCompanies")->getEntry($this->_user_session->servcompany->service_companies_id, array('languages_id' => $lang['id']));
		}
		
		return $res;
	}

	public function getCategoriesList() {
		$res = $this->_DP("List_Joined_Ep_ServiceCompCategories")->getList(null, null, array('active'=>1, 'languages_id' => self::$_user_language_id), array("name" => "ASC"));

		return $res['data'];
	}
	
	/**
	 * Обновляет указанную в первом параметре запись
	 * Данные для обновления передаются во втором параметре
	 * Функция возвращает количество фактически обновленных записей
	 *
	 * @param int $id
	 * @param array $data
	 * @return int
	 */
	public function updateEntry($id, Array $data_raw) {
		$id = $this->_user_session->servcompany->service_companies_id;
		
		if (empty($data_raw['common'])) { return -1; }
		$data_raw['common']['status'] = 1;

    //Zend_Debug::dump($data_raw);
    
    $changed = false;

		$langs_list = $this->_DP("List_Languages")->getList(null, null, array('active' => 1));
		foreach ($langs_list['data'] as $lang) {
      $entry = $this->_DP("List_Joined_Ep_ServiceCompanies")->
        getEntry($id, array('languages_id' => $lang['id']));
			
			/*--------------------logo------------------------------------------------*/
			$img_res = $this->updateImageLogoServ("service_companies", $id, $lang);

			if ($img_res) { $data_raw[$lang['code']]['logo'] = 1; } 
			//else { 	$data_raw[$lang['code']]['logo'] = 0; 	}
			
			/*-------------------------------------------------------------------*/
			
			if (empty($data_raw[$lang['code']])) { continue; }
			
			if (!$changed) {
        foreach ($data_raw['common'] as $key => $el) {
          if (isset($entry[$key]) && $el != $entry[$key]) {
            $changed = true; break;
          }
        }
        
        if (!$changed) {
          foreach ($data_raw[$lang['code']] as $key => $el) {
            if (isset($entry[$key]) && $el != $entry[$key]) {
              $changed = true; break;
            }
          }
        }
      }
			
			$data = array_merge($data_raw[$lang['code']], $data_raw['common']);
			
			$this->_DP_limit_params['languages_id'] = $lang['id'];
			
			$res = $this->_DP("List_Joined_Ep_ServiceCompanies")->updateEntry($id, $data, $this->_DP_limit_params);
		}

    if ($changed) $this->_sendNotification($id, $data, $tpl_code = 113);
		
		return 1;	
	}
	
	
	/**
	 * Обновляет логотип. Тип обновляемого логотипа указывается первым параметром.
	 * Возможные значения: service_companies, events, social_organizations, event_participants
	 * Далее можно указать ширину и высоту создаваемого логотипа
	 * В случае успеха возвращает true, неудачи - false, если логотип не был передан - null
	 *
	 * @param string $type
	 * @param int $id
	 * @param int $width
	 * @param int $height
	 * @return boolean|null
	 */
	protected function updateImageLogoServ($type, $id, $lang, $width=100, $height=50) 
	{

		$img_res = null;
		//Zend_Debug::dump($_FILES);
		//return 0;

		//Подготовка, проверка что файл логотипа загружен
		if (isset($_FILES) && isset($_FILES['logo']) && !$_FILES['logo']['error'][$lang['code']]) {
			//if ($_FILES['logo']['error'][$lang['code']]==0) 
			//{
				$file_fp = $_FILES['logo']['tmp_name'][$lang['code']];
					
				$save_as = PATH_FRONTEND_DATA_IMAGES . "/" . $type . "/logo/" . $lang['id'] . "/" . $id . ".jpg";
					
				$extraParams = array('image_type' => $_FILES['logo']['type'][$lang['code']]);
					
				$img_res = $this->_DP("Filesystem_Images")->thumbnailCreate($file_fp, $save_as, $width, $height, $extraParams);
				
			//}
			
			
			
		}
		
		//Zend_Debug::dump($img_res);
		//return 0;
		
		return $img_res;
	}	


	
  private function _sendNotification($id, $data, $tpl_code = 113) {
    require_once("Zend/Mail.php");
    require_once('Zend/Validate/EmailAddress.php');
    require_once(PATH_VIEWS . "/Frontend/View/Console.php");

    // $organizer_data = $this->_DP_obj->getEntry($id);

    // $hostValObj = new Zend_Validate_Hostname(Zend_Validate_Hostname::ALLOW_DNS, false);
    // $emailValObj = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS, false, $hostValObj);

    // $email = '';
    // if (!$emailValObj->isValid($email)) return false;

    $lang_id = 1;
    $tpl_data = $this->_DP('List_Joined_Pages')->getEntry($tpl_code, array('languages_id' => $lang_id));

    $mailObj = new Zend_Mail("utf-8");
    $view = new Frontend_View_Console();

    $view->assign("_system_include_conent", "contents/$tpl_code");

    $view->assign("servcomp", $data);

    $message = $view->fetch("system/email_base", $lang_id);

    $mailObj->setSubject($tpl_data['title']);
    $mailObj->setFrom("info@expopromoter.com", "ExpoPromoter Service");

    // $mailObj->addTo($email, !empty($pbluser_data['name']) ? $pbluser_data['name'] : $pbluser_data['login']);
    $mailObj->addto('gn@expopromogroup.com', 'Геннадий Нетяга');
    $mailObj->addBcc('alla.kostenko@expopromogroup.com', 'Алла Костенко');
    $mailObj->addBcc('lymar.vladimir@expopromogroup.com', 'Владимир Лымарь');
    $mailObj->addBcc('anastasiya.shumak@expopromogroup.com', 'Анастасия Шумак');
    $mailObj->addBcc('eugene.ivashin@expopromogroup.com', 'Евгений Ивашин');

    $mailObj->setBodyHtml($message);

    $mailObj->send();

    return true;
  }

}