<?PHP

Zend_Loader::loadClass("Admin_Ep_ModelAbstract", PATH_MODELS);

class Admin_Ep_EventsModel extends Admin_Ep_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_Events';

	/**
	 * Переопределяем для подготовки массива выбранных запросов
	 *
	 * @param int $id
	 * @return array
	 */
	public function getEntry($id) {

		$res = parent::getEntry($id);

		if (!empty($res) && isset($res['user_request_types'])) {
			if (!empty($res['user_request_types'])) {
				$res['user_request_types'] = explode(",", $res['user_request_types']);
				$res['user_request_types'] = array_flip($res['user_request_types']);
				foreach ($res['user_request_types'] as &$el) {
					$el = true;
				}
			} else {
				$res['user_request_types'] = array();
			}
		}

		return $res;
	}

	public function getAllLangsEntry($id) {
		$langs_list = $this->_DP("List_Languages")->getList();

		$res = array();

		foreach ($langs_list['data'] as $lang) {
			$res[$lang['code']] = $this->_DP_obj->getEntry($id, array('languages_id' => $lang['id']));
		}

		return $res;
	}

	/**
	 * Переопределяем для подготовки массива выбранных запросов
	 *
	 * @param int $id
	 * @param array $data
	 * @return int
	 */
	public function updateEntry($id, Array $data) {

		if (isset($data['_shelby_user_requests'])) {
			if (empty($data['user_request_types'])) {
				$data['user_request_types'] = '';
			} else {
				$data['user_request_types'] = new Zend_Db_Expr("('" . implode(",", $data['user_request_types']) . "')");
			}
		} else {
			unset($data['user_request_types']);
		}

		if (isset($data['web_address']) && !empty($data['web_address']) && strpos($data['web_address'], 'http://') === false) {
			$data['web_address'] = 'http://' . $data['web_address'];
		}
    
		if (isset($data['email']) && isset($data['brands_id'])) {
			$brand_data = array('email_requests' => $data['email']);
			$this->_DP('List_Joined_Ep_Brands')->updateEntry($data['brands_id'], $brand_data);
		}

		$img_res = $this->updateImageLogo("events", $id, 130, 130);

		if (!is_null($img_res)) {
			if ($img_res) {
				$data['logo'] = 1;
			} else {
				$data['logo'] = 0;
			}
		}

		$res = parent::updateEntry($id, $data);

		return max($img_res, $res);
	}

	public function insertEntry(Array $data, $insertAllLangs = false) {

		if (empty($data['user_request_types'])) {
			$data['user_request_types'] = '';
		} else {
			$data['user_request_types'] = new Zend_Db_Expr("('" . implode(",", $data['user_request_types']) . "')");
		}

		if (isset($data['web_address']) && !empty($data['web_address']) && strpos($data['web_address'], 'http://') === false) {
			$data['web_address'] = 'http://' . $data['web_address'];
		}

		if (isset($data['email']) && isset($data['brands_id'])) {
			$brand_data = array('email_requests' => $data['email']);
			$this->_DP('List_Joined_Ep_Brands')->updateEntry($data['brands_id'], $brand_data);
		}

		$res = parent::insertEntry($data, true);

		if ($res) {
			$id = $this->_DP_obj->getLastInsertId();

			$this->_DP("Filesystem_Files")->insertEntry(array('type' => 'dir', 'basePath' => PATH_FRONTEND_DATA_IMAGES, 'path' => "/events/", 'name' => $id));

			$img_res = $this->updateImageLogo("events", $id, 130, 130);

			if ($img_res) {
				$update_data = array('logo' => 1);
				$this->updateEntry($id, $update_data);
			}
		}

		return $res;
	}

	public function insertAllLangsEntry(Array $data_raw) {
		if (empty($data_raw['common'])) {
			return -1;
		}

		$id = null;
		$langs_list = $this->_DP("List_Languages")->getList();
		foreach ($langs_list['data'] as $lang) {

			if (empty($data_raw[$lang['code']])) {
				continue;
			}

			$data = array_merge($data_raw[$lang['code']], $data_raw['common']);
			$data = array_merge($data, $this->_DP_limit_params);
			$data['languages_id'] = $lang['id'];

			if (is_null($id)) {
				$res = $this->_DP_obj->insertEntry($data);

				if ($res != 1) {
					return $res;
				}

				$id = $this->_DP_obj->getLastInsertId();
				$data_raw['common']['id'] = $id;
			} else {
				$res = $this->_DP_obj->insertLanguageData($data);
			}

			//Добавление не удалось, прекращаем
			if (!$res) {
				return $res;
			}
		}

		return $res;
	}

	public function deleteEntry($id) {
		$res = parent::deleteEntry($id);

		if ($res) {
			$this->_DP("Filesystem_Files")->deleteRecursive(array($id), array('basePath' => PATH_FRONTEND_DATA_IMAGES . "/events/"));
		}

		return $res;
	}

	function getPeriodsList() {
		$res = $this->_DP("List_Joined_Ep_Periods")->getList(null, null, array('active'=>1, 'languages_id' => self::$_user_language_id));

		return $res['data'];
	}

	function getRequestsList() {
		return $this->_DP_obj->getRequestsList();
	}
}