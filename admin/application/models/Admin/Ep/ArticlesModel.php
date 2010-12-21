<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Ep_ArticlesModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_Articles';

	public function insertEntry(Array $data, $insertAllLangs = false) {
		$res = parent::insertEntry($data, true);

		if ($res) {
			$id = $this->_DP_obj->getLastInsertId();

			$this->_DP("Filesystem_Files")->insertEntry(array('type' => 'dir', 'basePath' => PATH_FRONTEND_DATA_IMAGES, 'path' => "/articles/", 'name' => $id));
		}

		return $res;
	}

	public function deleteEntry($id) {
		$res = parent::deleteEntry($id);

		if ($res) {
			$this->_DP("Filesystem_Files")->deleteRecursive(array($id), array('basePath' => PATH_FRONTEND_DATA_IMAGES . "/articles/"));
		}

		return $res;
	}

}