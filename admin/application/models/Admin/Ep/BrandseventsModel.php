<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Ep_BrandseventsModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_BrandPlusEvent';

	public function insertEntry(Array $data_raw, $insertAllLangs = false) {

		if (empty($data_raw['common'])) {
			return -1;
		}

		//Добавляем бренд
		$dpObj = $this->_DP("List_Joined_Ep_Brands");

		$brands_id = null;
		$langs_list = $this->getLanguagesList();
		foreach ($langs_list as $lang) {
			if (empty($data_raw[$lang['code']])) {
				continue;
			}

			$data = array_merge($data_raw[$lang['code']], $data_raw['common']);
			$data['languages_id'] = $lang['id'];

			if (is_null($brands_id)) {
				$res = $dpObj->insertEntry($data);

				if ($res != 1) {
					return $res;
				}

				$brands_id = $dpObj->getLastInsertId();
				$data_raw['common']['id'] = $brands_id;
				$data_raw['common']['brands_id'] = $brands_id;

				//Сохраняем выбранные категории и подкатегории
				if (isset($data['brands_categories_id'])) {
					$dpObj->updateCategories($brands_id, array($data['brands_categories_id']));
				}
				if (isset($data['brand_subcategories_id']) && is_array($data['brand_subcategories_id'])) {
					$dpObj->updateSubCategories($brands_id, $data['brand_subcategories_id']);
				}
			} else {
				$res = $dpObj->insertLanguageData($data);
			}

			//Добавление не удалось, прекращаем
			if (!$res) {
				return $res;
			}
		}
		unset($data_raw['common']['id']);

		//Добавляем событие
		$dpObj = $this->_DP("List_Joined_Ep_Events");

		$id = null;
		$langs_list = $this->getLanguagesList();
		foreach ($langs_list as $lang) {
			if (empty($data_raw[$lang['code']])) {
				continue;
			}

			$data = array_merge($data_raw[$lang['code']], $data_raw['common']);
			$data['languages_id'] = $lang['id'];

			if (is_null($id)) {
				$res = $dpObj->insertEntry($data);

				if ($res != 1) {
					return $res;
				}

				$id = $dpObj->getLastInsertId();
				$data_raw['common']['id'] = $id;
			} else {
				$res = $dpObj->insertLanguageData($data);
			}

			//Добавление не удалось, прекращаем
			if (!$res) {
				return $res;
			}
		}

		return $res;
	}


	public function getCategoriesList() {
		$res = $this->_DP("List_Joined_Ep_BrandsCategories")->getList(null, null,
		array('languages_id' => self::$_user_language_id), array("id" => "ASC"));
		return $res['data'];
	}

	function getPeriodsList() {
		$res = $this->_DP("List_Joined_Ep_Periods")->getList(null, null,
		array('active'=>1, 'languages_id' => self::$_user_language_id));
		return $res['data'];
	}

}