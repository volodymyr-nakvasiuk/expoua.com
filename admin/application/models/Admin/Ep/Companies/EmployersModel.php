<?PHP

Zend_Loader::loadClass("Admin_Ep_Companies_ModelAbstract", PATH_MODELS);

class Admin_Ep_Companies_EmployersModel extends Admin_Ep_Companies_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_CompaniesEmployers';

	public function insertEntry(Array $data, $insertAllLangs = false) {
		$res = parent::insertEntry($data, $insertAllLangs);

		if ($res) {
			$id = $this->_DP_obj->getLastInsertId();

			$img_res = $this->updateImageLogo("employers", $id, 130, 130);
			if ($img_res) {
				parent::updateEntry($id, array('photo' => 1));
			}
		}

		return $res;
	}

	public function updateEntry($id, Array $data) {

		$img_res = $this->updateImageLogo("employers", $id, 130, 130);

		if (!is_null($img_res)) {
			if ($img_res) {
				$data['photo'] = 1;
			} else {
				$data['photo'] = 0;
			}
		}

		$res = parent::updateEntry($id, $data);

		return max($res, $img_res);
	}

}