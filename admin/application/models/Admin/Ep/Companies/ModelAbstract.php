<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

abstract class Admin_Ep_Companies_ModelAbstract extends Admin_ListModelAbstract {

	public function getList($page = null, $parent = -1, $sort = null, $search = null) {

		if (!empty($parent) && $parent>0) {
			$this->_DP_limit_params['companies_id'] = $parent;
		}

		return parent::getList($page, -1, $sort, $search);
	}

	protected function updateImageLogo($type, $id, $width=130, $height=130) {

		$img_res = null;

		//Подготовка, проверка что файл логотипа загружен
		if (isset($_FILES) && isset($_FILES['logo']) && $_FILES['logo']['error']==0) {

			$file_fp = $_FILES['logo']['tmp_name'];

			$entry = $this->getEntry($id);

			if (empty($entry) || !isset($entry['companies_id'])) {
				return $img_res;
			}

			$save_as_base = PATH_FRONTEND_DATA_IMAGES . "/companies/";
			$save_as = $entry['companies_id'] . "/";

			switch ($type) {
				case "employers":
					$save_as .= "employers/";
					break;
				case "services":
					$save_as .= "services/logo/";
					$save_as_big = $save_as;
					break;
				case "galleries":
					$save_as .= "galleries/" . $entry['companies_services_id'] . "/";
					$save_as_big = $save_as;
					break;
				default:
					return $img_res;
			}

			$this->_DP("Filesystem_Images")->createRecursive(array('basePath' => $save_as_base, 'path' => $save_as));

			$save_as = $save_as_base . $save_as . $id . ".jpg";

			$extraParams = array('image_type' => $_FILES['logo']['type']);

			$img_res = $this->_DP("Filesystem_Images")->thumbnailCreate($file_fp, $save_as, $width, $height, $extraParams);
			//Для товаров/сервисов и галлерей сохраняем увеличенную копию
			if (($type == "services" || $type == "galleries") && $img_res) {
				$save_as = $save_as_base . $save_as_big . $id . "_big.jpg";
				$this->_DP("Filesystem_Images")->thumbnailCreate($file_fp, $save_as, 640, 480, $extraParams);
			}
		}

		return $img_res;
	}

}