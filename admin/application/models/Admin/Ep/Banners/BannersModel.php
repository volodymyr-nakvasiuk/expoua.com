<?PHP
require_once(PATH_MODELS . "/Admin/ListModelAbstract.php");

class Admin_Ep_Banners_BannersModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Banners_Banners';

	public function insertEntry(Array $data, $insertAllLangs = false) {
		$entry_type = $this->_DP("List_Banners_Types")->getEntry($data['types_id']);

		$res = parent::insertEntry($data, false);

		if ($entry_type['media'] == 'image' || $entry_type['media'] == 'flash' && $res) {
			$id = $this->_DP_obj->getLastInsertId();
			$res = $this->_updateBannerImage($id);

			if ($res == 0) {
				//Если с файлом что-то не так, удаляем запись
				$this->_DP_obj->deleteEntry(array($id));
			}
		}

		return $res;
	}

	public function updateEntry($id, Array $data) {
		$entry_type = $this->_DP("List_Banners_Types")->getEntry($data['types_id']);

		$res = parent::updateEntry($id, $data);

		if ($entry_type['media'] == 'image' || $entry_type['media'] == 'flash') {
			$this->_updateBannerImage($id);
		}

		//При обновлении баннера, обновляем материализованное представление баннеров
		$this->_DP("List_Banners_Plans")->updateMView();

		return 1;
	}

	public function deleteEntry($id) {
		$entry = $this->_DP_obj->getEntry($id);
		if (!empty($entry) && !empty($entry['file_name'])) {
			$extraParams['basePath'] = PATH_FRONTEND_DATA_IMAGES;
			$this->_DP("Filesystem_Files")->deleteEntry(array("/banners/" . $entry['file_name']), $extraParams);
		}

		$res = parent::deleteEntry($id);
		return $res;
	}

	private function _updateBannerImage($id) {
		$res = 1;
		if (isset($_FILES) && sizeof($_FILES) > 0) {
			$file = $_FILES;
			$file = array_pop($file);
			if (isset($file['error']) && $file['error']==0) {
				$file_info = pathinfo($file['name'], PATHINFO_EXTENSION);
				$filename = $id . "." . strtolower($file_info);
			} else {
				$res = 0;
			}
		} else {
			$res = 0;
		}

		if ($res != 0) {
			$this->_DP("Filesystem_Files")->insertEntry(array('type' => 'file', 'basePath' => PATH_FRONTEND_DATA_IMAGES, 'path' => "/banners/", 'name' => $filename));

			$this->_DP_obj->updateEntry($id, array('file_name' => $filename));
		}

		return $res;
	}

	public function getTypesList() {
		$res = $this->_DP("List_Banners_Types")->getList();
		return $res['data'];
	}
}