<?PHP

Zend_Loader::loadClass("Admin_Ep_ModelAbstract", PATH_MODELS);

class Admin_Ep_SocorgsModel extends Admin_Ep_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_Socorgs';

	public function insertEntry(Array $data, $insertAllLangs = false) {
		$res = parent::insertEntry($data, true);

		if ($res) {
			$id = $this->_DP_obj->getLastInsertId();

			$this->_DP("Filesystem_Files")->insertEntry(array('type' => 'dir', 'basePath' => PATH_FRONTEND_DATA_IMAGES, 'path' => "/social_organizations/", 'name' => $id));

			$img_res = $this->updateImageLogo("social_organizations", $id);

			if ($img_res) {
				$update_data = array('logo' => 1);
				$this->updateEntry($id, $update_data);
			}
		}

		return $res;
	}

	public function updateEntry($id, Array $data) {

		$img_res = $this->updateImageLogo("social_organizations", $id);

		if ($img_res) {
			$data['logo'] = 1;
		} else {
			$data['logo'] = 0;
		}

		return parent::updateEntry($id, $data);
	}

	public function deleteEntry($id) {
		$res = parent::deleteEntry($id);

		if ($res) {
			$this->_DP("Filesystem_Files")->deleteRecursive(array($id), array('basePath' => PATH_FRONTEND_DATA_IMAGES . "/social_organizations/"));
		}

		return $res;
	}
}