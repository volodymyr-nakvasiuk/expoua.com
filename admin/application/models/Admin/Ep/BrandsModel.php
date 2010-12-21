<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Ep_BrandsModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_Brands';

	public function getEntry($id) {
		$entry = parent::getEntry($id);

		$entry['categories'] = $this->_DP_obj->getSelectedCategoriesList($id);
		$entry['sub_categories'] = $this->_DP_obj->getSelectedSubCategoriesList($id);

		return $entry;
	}

	public function updateEntry($id, Array $data) {
		parent::updateEntry($id, $data);

		if (empty($data['cat'])) {
			$data['cat'] = array();
		}
		if (empty($data['subcat'])) {
			$data['subcat'] = array();
		}

		$this->_DP_obj->updateCategories($id, $data['cat']);
		$this->_DP_obj->updateSubCategories($id, $data['subcat']);

		return 1;
	}

	/**
	 * Вставка производится сразу всех языков
	 */
	public function insertEntry(Array $data_raw, $insertAllLangs = false) {

		if (empty($data_raw['common'])) {
			return -1;
		}

		$brand_id = null;
		$langs_list = $this->getLanguagesList();
		foreach ($langs_list as $lang) {

			if (empty($data_raw[$lang['code']])) {
				continue;
			}

			$data = array_merge($data_raw[$lang['code']], $data_raw['common']);
			$data['languages_id'] = $lang['id'];
				
			if (is_null($brand_id)) {
				$res = $this->_DP_obj->insertEntry($data);

				if ($res != 1) {
					return $res;
				}

				$brand_id = $this->_DP_obj->getLastInsertId();
				$data_raw['common']['id'] = $brand_id;
			} else {
				$res = $this->_DP_obj->insertLanguageData($data);
			}
				
			//Добавление не удалось, прекращаем
			if (!$res) {
				return $res;
			}
		}

		if (empty($data_raw['cat'])) {
			$data_raw['cat'] = array();
		}
		if (empty($data_raw['subcat'])) {
			$data_raw['subcat'] = array();
		}

		try {
			$this->_DP_obj->updateCategories($brand_id, $data_raw['cat']);
			$this->_DP_obj->updateSubCategories($brand_id, $data_raw['subcat']);
		} catch (Exception $e) {
			echo $e->getMessage();
			Zend_Debug::dump($data_raw);
			return 0;
		}

		return $res;
	}

	/**
	 * Возвращает список категорий и подкатегорий
	 *
	 * @return array
	 */
	public function getCategoriesList() {
		$cats = $this->_DP("List_Joined_Ep_BrandsCategories")->getList(null, null, array('languages_id' => self::$_user_language_id), array("id" => "ASC"));
		$cats = $cats['data'];

		$subcats_dp = $this->_DP("List_Joined_Ep_BrandsSubCategories");
		foreach ($cats as &$cat) {
			$subcats = $subcats_dp->getList(null, null, array('parent_id' => $cat['id'], 'languages_id' => self::$_user_language_id), array("id" => "ASC"));
			$cat['subcats'] = $subcats['data'];
		}

		return $cats;
	}
}