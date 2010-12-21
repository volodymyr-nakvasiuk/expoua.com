<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Ep_Companies_ManageModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_Companies';

	public function insertEntry(Array $data, $insertAllLangs = false) {
		$res = parent::insertEntry($data, true);

		if ($res==1) {
			$id = $this->_DP_obj->getLastInsertId();

			//Подготавливаем связи с событиями
			if (!empty($data['companies_to_events']) && is_array($data['companies_to_events'])) {
				$this->_DP_obj->updateEventsList($id, $data['companies_to_events']);
			}

			//Подготавливаем связи с категориями
			if (!empty($data['companies_to_brands_categories']) && is_array($data['companies_to_brands_categories'])) {
				//Удаляем дубликаты
				$update_list = array_unique($data['companies_to_brands_categories']);
				$this->_DP_obj->updateLinkedCategoriesList($id, $update_list);
			}

			//Загружаем логотип
			$img_res = $this->updateImageLogo("companies", $id, 130, 130);
			if ($img_res) {
				parent::updateEntry($id, array('logo' => 1));
			}

		}

		return $res;
	}

	public function getEntry($id) {
		$res = parent::getEntry($id);

		$res['list_events'] = $this->_DP_obj->getEventsList($id);
		$res['list_categories'] = $this->_DP_obj->getLinkedCategoriesList($id);
		return $res;
	}

	public function updateEntry($id, Array $data) {

		//Подготавливаем связи с событиями
		if (isset($data['companies_to_events']) && is_array($data['companies_to_events'])) {
			$this->_DP_obj->updateEventsList($id, $data['companies_to_events']);
		}

		//Подготавливаем связи с категориями
		if (isset($data['companies_to_brands_categories']) && is_array($data['companies_to_brands_categories'])) {
			//Удаляем дубликаты
			$update_list = array_unique($data['companies_to_brands_categories']);
			$this->_DP_obj->updateLinkedCategoriesList($id, $update_list);
		}

		$img_res = $this->updateImageLogo("companies", $id, 130, 130);

		if (!is_null($img_res)) {
			if ($img_res) {
				$data['logo'] = 1;
			} else {
				$data['logo'] = 0;
			}
		}

		parent::updateEntry($id, $data);

		return 1;
	}

	public function deleteEntry($id) {
		$res = parent::deleteEntry($id);

		if ($res) {
			$this->_DP("Filesystem_Files")->deleteRecursive(array($id), array('basePath' => PATH_FRONTEND_DATA_IMAGES . "/companies/"));
		}

		return $res;
	}

	public function getCategoriesList() {
		$cats = $this->_DP("List_Joined_Ep_BrandsCategories")->getList(null, null, array('languages_id' => self::$_user_language_id), array("id" => "ASC"));
		return $cats['data'];
	}

	protected function updateImageLogo($type, $id, $width=130, $height=130) {

		$img_res = null;

		//Подготовка, проверка что файл логотипа загружен
		if (isset($_FILES) && isset($_FILES['logo']) && $_FILES['logo']['error']==0) {

			$file_fp = $_FILES['logo']['tmp_name'];

			$save_as_base = PATH_FRONTEND_DATA_IMAGES . "/companies/";
			$save_as = $id . "/";

			$this->_DP("Filesystem_Images")->createRecursive(array('basePath' => $save_as_base, 'path' => $save_as));

			$save_as = $save_as_base . $save_as . "logo.jpg";

			$extraParams = array('image_type' => $_FILES['logo']['type']);

			$img_res = $this->_DP("Filesystem_Images")->thumbnailCreate($file_fp, $save_as, $width, $height, $extraParams);
		}

		return $img_res;
	}
}