<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Ep_DraftsModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_EventsDrafts';

	protected $_DP_limit_params = array('status' => 1);

	public function insertEntry(Array $data_raw, $insertAllLangs = false) {
		if (empty($data_raw['common'])) {
			return -1;
		}

		//Добавляем новый бренд
		if ($data_raw['_shelby_brand_action'] == "new") {
			$brand_id = $this->_insertBrand($data_raw);
			if (empty($brand_id)) {
				echo "Unable to add new brand.";
				return 0;
			}
			$data_raw['common']['brands_id'] = $brand_id;
		}

		//$data_raw['common']['active'] = 1;

		$obj_events = $this->_DP("List_Joined_Ep_Events");

		$id = null;
		$langs_list = $this->_DP("List_Languages")->getList(null, null, array('active' => 1));

		foreach ($langs_list['data'] as $lang) {

			if (empty($data_raw[$lang['code']])) {
				continue;
			}

			$data = array_merge($data_raw[$lang['code']], $data_raw['common']);
			$data = array_merge($data, $this->_DP_limit_params);
			$data['languages_id'] = $lang['id'];

			if (isset($data['web_address']) && !empty($data['web_address']) && strpos($data['web_address'], 'http://') === false) {
				$data['web_address'] = 'http://' . $data['web_address'];
			}

			if ($data_raw['_shelby_brand_action'] == "update") {
				//Обновление существующего
				$this->_updateBrand($data['brands_id'], array(
					'brands_categories_id' => $data['brand_categories_id'],
					'organizers_id' => $data['brand_organizers_id'],
					'name' => $data['brand_name_new'],
					'name_extended' => $data['brand_name_extended_new'],
					'email_requests' => $data['email'],
				), $lang['id']);
			} else if (isset($data['email'])) {
				$brand_data = array('email_requests' => $data['email']);
				$this->_DP('List_Joined_Ep_Brands')->updateEntry($data['brands_id'], $brand_data);
			}

			if (is_null($id)) {
				$res = $obj_events->insertEntry($data);

				if ($res != 1) {
					return $res;
				}

				$id = $obj_events->getLastInsertId();
				$data_raw['common']['id'] = $id;

			} else {
				$res = $obj_events->insertLanguageData($data);
			}

			//Добавление не удалось, прекращаем
			if (!$res) {
				return $res;
			}
			
			//Логотип
			if (isset($data['logo']) && $data['logo'] == 1) {
				rename(
					PATH_FRONTEND_DATA_IMAGES . "/drafts_events/logo/" . $lang['id'] . "/" . $data_raw['draft_id'] . ".jpg",
					PATH_FRONTEND_DATA_IMAGES . "/events/logo/" . $lang['id'] . "/" . $id . ".jpg"
				);
			}
		}


		//Добавление прошло успешно, удаляем черновик
		if ($res) {
			$this->_sendNotification($data_raw);

			$identity = Zend_Auth::getInstance()->getIdentity();
			$user_id = $this->_DP("List_AclAdmins")->getUserIdByLogin($identity);

			$data_draft = $this->_DP("List_Joined_Ep_EventsDrafts")->getEntry($data_raw['draft_id']);

			$data_log = array(
				'users_operators_id' => $data_draft['users_operators_id'],
				'acl_admin_users_id' => $user_id,
				'type' => ($data_raw['accept_type']=='high' ? 'accept_high':'accept'),
				'events_id' => $id
			);
			$this->_DP("List_LogOperators")->insertEntry($data_log);

			$this->_DP_obj->deleteEntry(array($data_raw['draft_id']));
		}

		return $res;
	}


	public function updateEntry($id, Array $data) {
		unset($this->_DP_limit_params['status']);

		$identity = Zend_Auth::getInstance()->getIdentity();
		$user_id = $this->_DP("List_AclAdmins")->getUserIdByLogin($identity);

		if (isset($data['status']) && $data['status'] == -1) {
			$data_draft = $this->_DP("List_Joined_Ep_EventsDrafts")->getEntry($id);
				
			//Zend_Debug::dump($data_draft);
				
			$data_log = array(
				'users_operators_id' => $data_draft['users_operators_id'],
				'acl_admin_users_id' => $user_id,
				'type' => 'cancel',
				'events_id' => $data_draft['events_id']
			);
			$this->_DP("List_LogOperators")->insertEntry($data_log);
		}

		return parent::updateEntry($id, $data);
	}


	/**
	 * Обновление информации о событии
	 *
	 * @param int $id
	 * @param array $data_raw
	 * @return int
	 */
	public function updateEventEntry($id, Array $data_raw) {
		if (empty($data_raw['common'])) {
			return -1;
		}

		//Добавляем новый бренд
		if ($data_raw['_shelby_brand_action'] == "new") {
			$brand_id = $this->_insertBrand($data_raw);
			if (empty($brand_id)) {
				echo "Unable to add new brand.";
				return 0;
			}
			$data_raw['common']['brands_id'] = $brand_id;
		}
		
		$obj_events = $this->_DP("List_Joined_Ep_Events");

		$langs_list = $this->_DP("List_Languages")->getList();

		foreach ($langs_list['data'] as $lang) {
			if (empty($data_raw[$lang['code']])) {
				continue;
			}

			$data = array_merge($data_raw[$lang['code']], $data_raw['common']);
			unset($data['languages_id']);

			// print_r($data);
				
			$res = $obj_events->updateEntry($id, $data, array('languages_id' => $lang['id']));

			if ($data_raw['_shelby_brand_action'] == "update") {
				//Обновление существующего
				$this->_updateBrand($data['brands_id'], array(
					'brands_categories_id' => $data['brand_categories_id'],
					'organizers_id' => $data['brand_organizers_id'],
					'name' => $data['brand_name_new'],
					'name_extended' => $data['brand_name_extended_new'],
					'email_requests' => $data['email'],
				), $lang['id']);
			} else {
				//Обновление ТОЛЬКО email
				$this->_updateBrand($data['brands_id'], array(
					'email_requests' => $data['email'],
				), $lang['id']);
			}
		}

		//Обновление прошло успешно, удаляем черновик
		if ($res == 1 || $res == 0) {
			$this->_sendNotification($data_raw);

			if ($res == 1) {

				$identity = Zend_Auth::getInstance()->getIdentity();
				$user_id = $this->_DP("List_AclAdmins")->getUserIdByLogin($identity);

				$data_draft = $this->_DP("List_Joined_Ep_EventsDrafts")->getEntry($data_raw['draft_id']);
				$data_log = array(
					'users_operators_id' => $data_draft['users_operators_id'],
					'acl_admin_users_id' => $user_id,
					'type' => ($data_raw['accept_type']=='high' ? 'accept_high':'accept'),
					'events_id' => $id
				);
				$this->_DP("List_LogOperators")->insertEntry($data_log);
			}

			$this->_DP_obj->deleteEntry(array($data_raw['draft_id']));
			return 1;
		}

		return $res;
	}


	private function _insertBrand($data_raw) {
		$id = null;
		$langs_list = $this->_DP("List_Languages")->getList(null);
		$data = array();

		foreach ($langs_list['data'] as $lang) {
			$data = array(
					'brands_categories_id' => $data_raw['common']['brand_categories_id'],
					'organizers_id' => $data_raw['common']['brand_organizers_id'],
 					'email_requests' => $data_raw['en']['email'],
					'name' => isset($data_raw[$lang['code']]['brand_name_new']) ? $data_raw[$lang['code']]['brand_name_new'] : '',
					'name_extended' => isset($data_raw[$lang['code']]['brand_name_extended_new']) ? $data_raw[$lang['code']]['brand_name_extended_new'] : '',
					'languages_id' => $lang['id']);

			if (is_null($id)) {
				$res = $this->_DP("List_Joined_Ep_Brands")->insertEntry($data);

				if ($res != 1) {
					return false;
				}

				$id = $this->_DP("List_Joined_Ep_Brands")->getLastInsertId();
			} else {
				$data['id'] = $id;
				$res = $this->_DP("List_Joined_Ep_Brands")->insertLanguageData($data);
			}
		}


		if (!empty($data_raw["common"]["brand_subcategories_id"]) && is_array($data_raw["common"]["brand_subcategories_id"])) {
			//Если есть выбранные подкатегории, отмечаем только их
			$subcats = $data_raw["common"]["brand_subcategories_id"];
		} else {
			//Отмечаем все подгатегории для выбранной главной категории
			$this->_DP('List_Joined_Ep_Brands')->updateCategories($id, array($data['brands_categories_id']));
			$subcats_raw = $this->_DP('List_Joined_Ep_BrandsSubCategories')->getList(null, null,
			array('active'=>1, 'parent_id'=>$data['brands_categories_id'], 'languages_id' => self::$_user_language_id));

			$subcats = array();
			foreach($subcats_raw['data'] as $el) {
				$subcats[] = $el['id'];
			}
		}

		$this->_DP('List_Joined_Ep_Brands')->updateSubCategories($id, $subcats);

		return $id;
	}


	private function _updateBrand($id, Array $data, $languages_id) {
		return $this->_DP("List_Joined_Ep_Brands")->updateEntry($id, $data, array('languages_id' => $languages_id));
	}


	public function getEntry($id) {
		$langs_list = $this->_DP("List_Languages")->getList();

		$res = array();

		foreach ($langs_list['data'] as $lang) {
			$res[$lang['code']] = $this->_DP("List_Joined_Ep_EventsDrafts")->getEntry($id, array('languages_id' => $lang['id']));
		}

		$res['brands_subcategories'] = $this->_DP_obj->getSelectedSubCategoriesList($id);

		//Zend_Debug::dump($res);

		return $res;
	}


	public function getEventEntry($id) {
		$langs_list = $this->_DP("List_Languages")->getList();

		$res = array();

		foreach ($langs_list['data'] as $lang) {
			$res[$lang['code']] = $this->_DP("List_Joined_Ep_Events")->getEntry($id, array('languages_id' => $lang['id']));
		}

		return $res;
	}

	public function getPeriodsList() {
		$res = $this->_DP("List_Joined_Ep_Periods")->getList(null, null, array('active'=>1, 'languages_id' => self::$_user_language_id));

		return $res['data'];
	}



	private function _sendNotification($data) {
		require_once("Zend/Mail.php");
		require_once('Zend/Validate/EmailAddress.php');
		require_once(PATH_VIEWS . "/Frontend/View/Console.php");

		$data_draft = $this->_DP("List_Joined_Ep_EventsDrafts")->getEntry($data['draft_id']);
		$draft_creator = $this->_DP("List_Operators")->getEntry($data_draft['users_operators_id']);

		if ($draft_creator['type'] != 'organizer') return false;

		$hostValObj = new Zend_Validate_Hostname(Zend_Validate_Hostname::ALLOW_DNS, false);
		$emailValObj = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS, false, $hostValObj);

		$email = $draft_creator['email'];
		$adressee = !empty($draft_creator['organizer_manual_name']) ? $draft_creator['organizer_manual_name'] : $draft_creator['login'];

		if (!$emailValObj->isValid($email)) {
			$email = $data['en']['cont_pers_email'];
			$adressee = '';

			if (!$emailValObj->isValid($email)) {
				$email = $data['en']['email'];

				if (!$emailValObj->isValid($email)) return false;
			}
		}

		$tpl_data = $this->_DP('List_Joined_Pages')->getEntry(105);

		$mailObj = new Zend_Mail("utf-8");
		$view = new Frontend_View_Console();

		$view->assign("_system_include_conent", "contents/105");

		$data['update_time'] = date('F d, Y', strtotime('+1 day'));

		$view->assign("data", $data);

		$message = $view->fetch("system/email_base");

		$mailObj->setSubject($tpl_data['title']);
		$mailObj->setFrom("info@expopromoter.com", "EGMS ExpoPromoter.com");

		$mailObj->addTo($email, $adressee);

		// $mailObj->addBcc('eugene.ivashin@expopromogroup.com');

		$mailObj->setBodyHtml($message);

		$mailObj->send();

		return true;
	}

}
