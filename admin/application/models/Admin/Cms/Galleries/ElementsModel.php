<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Cms_Galleries_ElementsModel extends Admin_ListModelAbstract {

	protected $_DP_name = "List_Joined_GalleriesElements";

	public function insertEntry(Array $data, $insertAllLangs = false) {

		//Получаем информацию о текущей галлерее
		$entry_gallery = $this->_DP("List_Galleries")->getEntry($data['parent_id']);

		//Проверяем, существует ли такая галлерея
		if (empty($entry_gallery)) {
			return -3;
		}

		//Подготовка, проверка что файл загружен
		if (isset($_FILES) && sizeof($_FILES) > 0) {
			$files = $_FILES;
			$files = array_pop($files);

			if (isset($files['error']) && $files['error']==0) {
				$extension = strtolower(pathinfo($files['name'], PATHINFO_EXTENSION));
			} else {
				return -2;
			}

		} else {
			return -1;
		}

		//Добавляем запись в БД и получаем индекс добавленной записи
		$res = parent::insertEntry($data, $insertAllLangs = false);
		$lastId = $this->_DP_obj->getLastInsertId();

		//Если запись успешно добавлена, сохраняем изображение
		if ($res && $lastId) {

			$fileData = array('type' => 'file', 'name' => $lastId . "." . $extension);
			$fileData['basePath'] = PATH_FRONTEND_DATA_GALLERIES . "/";
			$fileData['path'] = $data['parent_id'] . "/";
			$image_name = $fileData['basePath'] . $fileData['path'] . $fileData['name'];

			$this->_DP("Filesystem_Images")->insertEntry($fileData);

			//Получаем информацию об изображении
			$image_info = $this->_DP("Filesystem_Images")->getImageInfo($image_name);
			//Обновляем только что добавленную запись сведениями об изображении
			$updateData = array('image_height' => $image_info['height'], 'image_width' => $image_info['width'], 'filename' => $fileData['name']);
			$this->_DP_obj->updateEntry($lastId, $updateData);

			//Если нужно, создаем уменьшенное изображение
			if ($entry_gallery['thumbnail_create'] == 1 && ($entry_gallery['thumbnail_width']>0 || $entry_gallery['thumbnail_height']>0)) {

				$tb_name = $fileData['basePath'] . $fileData['path'] . "tb/" . $fileData['name'];
				$extraParams = array('image_type' => $files['type']);

				$this->_DP("Filesystem_Images")->thumbnailCreate($image_name, $tb_name, $entry_gallery['thumbnail_width'], $entry_gallery['thumbnail_height'], $extraParams);
			}
		}

		return $res;
	}

	public function deleteEntry($id) {

		$entry = $this->_DP_obj->getEntry($id);

		if (empty($entry)) {
			return -1;
		}

		//Удаляем изображения
		$path = PATH_FRONTEND_DATA_GALLERIES . "/" . $entry['parent_id'] . "/";
		$this->_DP("Filesystem_Images")->deleteEntry(array($entry['filename']), array('basePath' => $path));
		$path .= "/tb/";
		$this->_DP("Filesystem_Images")->deleteEntry(array($entry['id'] . ".jpg"), array('basePath' => $path));

		return parent::deleteEntry($id);
	}
}