<?PHP

Zend_Loader::loadClass("Admin_Ep_ModelAbstract", PATH_MODELS);

class Admin_Ep_ExpocentersModel extends Admin_Ep_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_Expocenters';

	public function insertEntry(Array $data, $insertAllLangs = false) {
    if (isset($data['web_address']) && !empty($data['web_address']) && strpos($data['web_address'], 'http://') === false) {
      $data['web_address'] = 'http://' . $data['web_address'];
    } 

		$res = parent::insertEntry($data, true);

		if ($res) {
			$id = $this->_DP_obj->getLastInsertId();

			$this->_DP("Filesystem_Files")->insertEntry(array('type' => 'dir', 'basePath' => PATH_FRONTEND_DATA_IMAGES, 'path' => "/expocenters/", 'name' => $id));

			$img_res = $this->updateImageLogo("expocenters", $id);

			$update_data = array();
			if ($img_res) {
				$update_data = array('logo' => 1);
			}

			$data_images = $this->_updatePlanMapViewImages($id);
			$update_data = array_merge($update_data, $data_images);

			if (!empty($update_data)) {
				$this->updateEntry($id, $update_data);
			}
		}

		return $res;
	}

	public function updateEntry($id, Array $data) {
    if (isset($data['web_address']) && !empty($data['web_address']) && strpos($data['web_address'], 'http://') === false) {
      $data['web_address'] = 'http://' . $data['web_address'];
    } 

		$img_res = $this->updateImageLogo("expocenters", $id);

		if (!is_null($img_res)) {
			if ($img_res) {
				$data['logo'] = 1;
			} else {
				$data['logo'] = 0;
			}
		}

		$data_images = $this->_updatePlanMapViewImages($id);

		$data = array_merge($data, $data_images);
		parent::updateEntry($id, $data);

		return 1;
	}

	public function deleteEntry($id) {
		$res = parent::deleteEntry($id);

		if ($res) {
			$this->_DP("Filesystem_Files")->deleteRecursive(array($id), array('basePath' => PATH_FRONTEND_DATA_IMAGES . "/expocenters/"));
		}

		return $res;
	}

	private function _updatePlanMapViewImages($id) {
		$res = array();

		$this->_DP("Filesystem_Images")->createRecursive(array('basePath' => PATH_FRONTEND_DATA_IMAGES . "/expocenters/", 'path' => $id));

		//Карта проезда
		if (isset($_FILES) && isset($_FILES['map']) && $_FILES['map']['error']==0) {
			$array_files = $_FILES['map'];
			$ext = $this->_getExtension($array_files['name']);
			$file_name = "map_" . self::$_user_language_id . "." . $ext;
			$save_as = PATH_FRONTEND_DATA_IMAGES . "/expocenters/" . $id . "/" . $file_name;
			$tmp = move_uploaded_file($array_files['tmp_name'], $save_as);
			if ($tmp) {
				$res['image_map'] = $file_name;
			} else {
				$res['image_map'] = '';
			}
		}

		//План комплекса
		if (isset($_FILES) && isset($_FILES['plan']) && $_FILES['plan']['error']==0) {
			$array_files = $_FILES['plan'];
			$ext = $this->_getExtension($array_files['name']);
			$file_name = "plan_" . self::$_user_language_id . "." . $ext;
			$save_as = PATH_FRONTEND_DATA_IMAGES . "/expocenters/" . $id . "/" . $file_name;
			$tmp = move_uploaded_file($array_files['tmp_name'], $save_as);
			if ($tmp) {
				$res['image_plan'] = $file_name;
			} else {
				$res['image_plan'] = '';
			}
		}

		if (isset($_FILES) && isset($_FILES['view']) && $_FILES['view']['error']==0) {
			$array_files = $_FILES['view'];
			$ext = $this->_getExtension($array_files['name']);
			$file_name = "view_" . self::$_user_language_id . "." . $ext;
			$save_as = PATH_FRONTEND_DATA_IMAGES . "/expocenters/" . $id . "/" . $file_name;
			$tmp = move_uploaded_file($array_files['tmp_name'], $save_as);
			if ($tmp) {
				$res['image_view'] = $file_name;
			} else {
				$res['image_view'] = '';
			}
		}

		return $res;
	}

	private function _getExtension($fname) {
		$ext = explode(".", $fname);

		if (sizeof($ext) < 2) {
			return 'jpg';
		}

		$ext = $ext[sizeof($ext) - 1];

		return $ext;
	}
}