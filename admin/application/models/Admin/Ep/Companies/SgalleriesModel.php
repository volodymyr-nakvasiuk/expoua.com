<?PHP

Zend_Loader::loadClass("Admin_Ep_Companies_ModelAbstract", PATH_MODELS);

class Admin_Ep_Companies_SgalleriesModel extends Admin_Ep_Companies_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_CompaniesGalleries';

	public function insertEntry(Array $data, $insertAllLangs = false) {
		$res = parent::insertEntry($data, $insertAllLangs);

		if ($res) {
			$id = $this->_DP_obj->getLastInsertId();

			$img_res = $this->updateImageLogo("galleries", $id, 130, 130);
		}

		return $res;
	}

	public function updateEntry($id, Array $data) {

		$img_res = $this->updateImageLogo("galleries", $id, 130, 130);

		$res = parent::updateEntry($id, $data);

		return max($res, $img_res);
	}

}