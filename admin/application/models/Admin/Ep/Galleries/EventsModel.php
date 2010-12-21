<?PHP

require_once(PATH_MODELS . "/Admin/ListModelAbstract.php");

class Admin_Ep_Galleries_EventsModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_EventsGalleries';

	public function getEventEntry($id) {
		return $this->_DP("List_Joined_Ep_Events")->getEntry($id);
	}

	public function getList($page = null, $parent = -1, $sort = null, $search = null) {

		if (!empty($parent) && $parent != -1) {
			$this->_DP_limit_params['events_id'] = $parent;
		}

		return parent::getList($page, $sort, $search);
	}

	public function insertEntry(Array $data, $insertAllLangs = false) {

		if (!isset($_FILES) || !isset($_FILES['image']) || $_FILES['image']['error']!=0) {
			return -1;
		}

		$data['events_id'] = intval($data['parent_id']);
		unset($data['parent_id']);

		$res = parent::insertEntry($data, $insertAllLangs);

		if (!$res) {
			return $res;
		}

		$gallery_id = $this->_DP_obj->getLastInsertId();
		$file_fp = $_FILES['image']['tmp_name'];
		$save_as_base = PATH_FRONTEND_DATA_IMAGES . "/events/" . $data['events_id'] . "/";
		$this->_DP("Filesystem_Images")->createRecursive(array('basePath' => $save_as_base, 'path' => "gallery/"));

		$save_as_base = $save_as_base . "gallery/";

		$save_as = $save_as_base . $gallery_id . "_tb.jpg";
		$extraParams = array('image_type' => $_FILES['image']['type']);
		$img_res = $this->_DP("Filesystem_Images")->thumbnailCreate($file_fp, $save_as, 100, 100, $extraParams);

		$save_as = $save_as_base . $gallery_id . ".jpg";
		$this->_DP("Filesystem_Images")->thumbnailCreate($file_fp, $save_as, 640, 480, $extraParams);

		return $res;
	}

	public function deleteEntry($id) {

		$entry = $this->getEntry($id);

		if (empty($entry)) {
			return 0;
		}

		$res = parent::deleteEntry($id);

		if ($res) {
			//Удаляем изображения
			$path = PATH_FRONTEND_DATA_IMAGES . "/events/" . $entry['events_id'] . "/gallery/";
			$this->_DP("Filesystem_Images")->deleteEntry(array($entry['id'] . ".jpg"), array('basePath' => $path));
			$this->_DP("Filesystem_Images")->deleteEntry(array($entry['id'] . "_tb.jpg"), array('basePath' => $path));
		}

		return $res;
	}
}