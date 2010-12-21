<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

abstract class Admin_Ep_ModelAbstract extends Admin_ListModelAbstract {

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
	protected function updateImageLogo($type, $id, $width=100, $height=50) {

		$img_res = null;

		//Подготовка, проверка что файл логотипа загружен
		if (isset($_FILES) && isset($_FILES['logo']) && $_FILES['logo']['error']==0) {

			//$this->_DP("Filesystem_Files")->insertEntry(array('type' => 'dir', 'basePath' => PATH_FRONTEND_DATA_IMAGES, 'path' => "/" . $type . "/", 'name' => $id));

			$file_fp = $_FILES['logo']['tmp_name'];

			if ($type=="event_participants") {
				$save_as = PATH_FRONTEND_DATA_IMAGES . "/" . $type . "/logo/" . $id . ".jpg";
			} else {
				$save_as = PATH_FRONTEND_DATA_IMAGES . "/" . $type . "/logo/" . self::$_user_language_id . "/" . $id . ".jpg";
			}

			$extraParams = array('image_type' => $_FILES['logo']['type']);

			$img_res = $this->_DP("Filesystem_Images")->thumbnailCreate($file_fp, $save_as, $width, $height, $extraParams);
		}

		return $img_res;
	}

}