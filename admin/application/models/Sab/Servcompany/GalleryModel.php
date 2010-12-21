<?PHP

Zend_Loader::loadClass("Sab_Servcompany_ModelAbstract", PATH_MODELS);

class Sab_Servcompany_GalleryModel extends Sab_Servcompany_ModelAbstract {

	protected $_DP_name = 'List_ServiceCompaniesGalleries';

  public $forceListResults = 1000;

	
	
	public function getList($page = null, $sort = null, $search = null) {
		//Zend_Debug::dump($this->_DP_limit_params);

    $list = $this->_DP_obj->getList($this->forceListResults, 1, $this->_DP_limit_params);

		return $list;
	}


	public function getEntry($id) {
		return $this->_DP_obj->getEntry($id);
	}


	public function insertEntry(Array $data, $insertAllLangs = false) {
		if (!isset($_FILES) || !isset($_FILES['image']) || $_FILES['image']['error']!=0) {
			return -1;
		}

		$data['servcomps_id'] = $this->servcomp_id;

    Zend_Debug::dump($data);

		$res = parent::insertEntry($data);

		if (!$res) {
			return $res;
		}

		$gallery_id = $this->_DP_obj->getLastInsertId();
		$file_fp = $_FILES['image']['tmp_name'];
		$save_as_base = PATH_FRONTEND_DATA_IMAGES . "/servcomps/" . $this->servcomp_id . "/";
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
		$res = parent::deleteEntry($id);

		if ($res) {
			//Удаляем изображения
			$path = PATH_FRONTEND_DATA_IMAGES . "/servcomps/" . $this->servcomp_id . "/gallery/";
			$this->_DP("Filesystem_Images")->deleteEntry(array($id . ".jpg"), array('basePath' => $path));
			$this->_DP("Filesystem_Images")->deleteEntry(array($id . "_tb.jpg"), array('basePath' => $path));
		}

		return $res;
	}
}
