<?PHP

Zend_Loader::loadClass("Sab_Company_ModelAbstract", PATH_MODELS);

class Sab_Company_NewsModel extends Sab_Company_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_CompaniesNews';

	public function updateEntry($id, Array $data) {
		$img_res = $this->updateImageLogo("news", $id, 130, 130);

		if (!is_null($img_res)) {
			if ($img_res) {
				$data['common']['logo'] = 1;
			} else {
				$data['common']['logo'] = 0;
			}
		}

		$res = parent::updateEntry($id, $data);

		return max($res, $img_res);
	}
	
	public function insertEntry(Array $data, $insertAllLangs = false) {
		$res = parent::insertEntry($data, $insertAllLangs);

		if ($res) {
			$id = $this->_DP_obj->getLastInsertId();

			$img_res = $this->updateImageLogo("news", $id, 130, 130);
			if ($img_res) {
				$this->_DP_obj->updateEntry($id, array('logo' => 1), $this->_DP_limit_params);
			}
		}

		return $res;
	}
}