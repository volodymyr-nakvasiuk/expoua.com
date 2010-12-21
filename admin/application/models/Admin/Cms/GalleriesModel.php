<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Cms_GalleriesModel extends Admin_ListModelAbstract {

	protected $_DP_name = "List_Galleries";

	public function insertEntry(Array $data, $insertAllLangs = false) {
		$res = parent::insertEntry($data, $insertAllLangs = false);

		//Если запись добавлена успешно, создаем необходимые каталоги
		if ($res == 1) {
			$lastId = $this->_DP_obj->getLastInsertId();
			$params = array('type' => 'dir', 'basePath' => PATH_FRONTEND_DATA_GALLERIES, 'path' => '/', 'name' => $lastId);
			$this->_DP("Filesystem_Images")->insertEntry($params);

			$params['path'] = '/' . $lastId . '/';
			$params['name'] = 'tb';
			$this->_DP("Filesystem_Images")->insertEntry($params);
		}

		return $res;
	}

	public function deleteEntry($id) {
		$res = parent::deleteEntry($id);

		//Запись удалена из БД, удаляем все каталоги и файлы, относящиеся к данной галлерее
		if ($res == 1) {
			if (!is_array($id)) {
				$id = array($id);
			}

			$params = array('basePath' => PATH_FRONTEND_DATA_GALLERIES . "/");

			$this->_DP("Filesystem_Images")->deleteRecursive($id, $params);
		}

		return $res;
	}

}