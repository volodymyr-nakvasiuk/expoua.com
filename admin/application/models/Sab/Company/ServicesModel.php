<?PHP

Zend_Loader::loadClass("Sab_Company_ModelAbstract", PATH_MODELS);

class Sab_Company_ServicesModel extends Sab_Company_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_CompaniesServices';

	public function updateEntry($id, Array $data) {

		$img_res = $this->updateImageLogo("services", $id, 130, 130);

		if (!is_null($img_res)) {
			if ($img_res) {
				$data['common']['photo'] = 1;
			} else {
				$data['common']['photo'] = 0;
			}
		}

		$res = parent::updateEntry($id, $data);

		return max($res, $img_res);
	}

	public function insertEntry(Array $data, $insertAllLangs = false) {

		//Zend_Debug::dump($data);

		if (empty($data['common']['companies_services_cats_id'])) {
			$langs_list = $this->getUserLanguagesList();
			$category_data = array();
			foreach ($langs_list as $lang) {
				if (!empty($data[$lang['code']]['category_name'])) {
					$category_data[$lang['code']]['name'] = $data[$lang['code']]['category_name'];
				}
			}

			//Zend_Debug::dump($category_data);

			//Если оба языка заполнены, создаем новую категорию
			if (sizeof($category_data) == 2) {
				$DB_obj_tmp = $this->_DP_obj;
				$this->_DP_obj = $this->_DP("List_Joined_Ep_CompaniesServicesCats");

				$category_data['common']['dummy'] = 1;
				$res = parent::insertEntry($category_data);

				if ($res) {
					$data['common']['companies_services_cats_id'] = $this->_DP_obj->getLastInsertId();
				}

				$this->_DP_obj = $DB_obj_tmp;
			}
		}

		$res = parent::insertEntry($data, $insertAllLangs);

		if ($res) {
			$id = $this->_DP_obj->getLastInsertId();

			$img_res = $this->updateImageLogo("services", $id, 130, 130);
			if ($img_res) {
				$this->_DP_obj->updateEntry($id, array('photo' => 1), $this->_DP_limit_params);
			}
		}

		return $res;
	}

	public function getCategoriesList() {
		/*$list = $this->_DP("List_Joined_Ep_CompaniesServicesCats")->getList(null, null, array('companies_id' => $this->getUserCompanyId(), 'languages_id' => self::$_user_language_id));

		return $list['data'];*/

		$params = array('active' => 1, 'languages_id' => self::$_user_language_id);
		$list = $this->_DP("List_Joined_Ep_BrandsCategories")->getList(null, null, $params, array('name' => 'ASC'));

		return $list['data'];
	}

  public function getSubCategoriesList($parent) {
  	$params = array('active' => 1, 'languages_id' => self::$_user_language_id, 'parent_id' => $parent);
  	$res = $this->_DP("List_Joined_Ep_BrandsSubCategories")->getList(null, null, $params, array('name' => 'ASC'));
  	return $res['data'];
  }

  public function getSubCategoryEntry($id) {
  	$params = array('active' => 1, 'languages_id' => self::$_user_language_id);
  	return $this->_DP("List_Joined_Ep_BrandsSubCategories")->getEntry($id, $params);
  }


	/*
	public function &getExhibitionCategoriesTree() {
		$params = array('active' => 1, 'languages_id' => self::$_user_language_id);
		$list = $this->_DP("List_Joined_Ep_BrandsCategories")->getList(null, null, $params, array('name' => 'ASC'));

		$list = $list['data'];

		foreach ($list as $key => &$cat) {
			$params['parent_id'] = $cat['id'];
			$tmp = $this->_DP("List_Joined_Ep_BrandsSubCategories")->getList(null, null, $params, array('name' => 'ASC'));
			if (empty($tmp['data'])) {
				unset($list[$key]);
			} else {
				$cat['subcategories'] = $tmp['data'];
			}
		}

		return $list;
	}*/
}