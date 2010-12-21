<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Ep_NewsModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_News';

	public function insertEntry(Array $data, $insertAllLangs = false) {
		$res = parent::insertEntry($data, true);

		if ($res) {
			$id = $this->_DP_obj->getLastInsertId();
			$this->_DP("Filesystem_Files")->insertEntry(array('type' => 'dir', 'basePath' => PATH_FRONTEND_DATA_IMAGES, 'path' => "/news/", 'name' => $id));
		}

		return $res;
	}

	public function deleteEntry($id) {
		$res = parent::deleteEntry($id);

		if ($res) {
			$this->_DP("Filesystem_Files")->deleteRecursive(array($id), array('basePath' => PATH_FRONTEND_DATA_IMAGES . "/news/"));
		}

		return $res;
	}

	public function getBrandCategoriesList() {
		return $this->_DP("List_Joined_Ep_BrandsCategories")->getList(null, null, array('active'=>1, 'languages_id' => self::$_user_language_id), array("name" => "ASC"));
	}

	public function getCountriesList() {
		return $this->_DP("List_Joined_Ep_Countries")->getList(null, null, array('active'=>1, 'languages_id' => self::$_user_language_id), array("name" => "ASC"));
	}

}