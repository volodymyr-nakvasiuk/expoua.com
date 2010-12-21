<?PHP

Zend_Loader::loadClass("Admin_Ep_ModelAbstract", PATH_MODELS);

class Admin_Ep_EventsparticipantsModel extends Admin_Ep_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_EventsParticipants';

	//Добавляем во все языки сразу
	public function insertEntry(Array $data, $insertAllLangs = false) {

		$res = parent::insertEntry($data, true);

		if ($res) {

			$id = $this->_DP_obj->getLastInsertId();
			$img_res = $this->updateImageLogo("event_participants", $id, 110, 80);

			if ($img_res) {
				$update_data = array('logo' => 1);
				$this->updateEntry($id, $update_data);
			}
		}

		return $res;
	}

	public function updateEntry($id, Array $data) {

		$img_res = $this->updateImageLogo("event_participants", $id, 110, 80);

		if (!is_null($img_res)) {
			if ($img_res) {
				$data['logo'] = 1;
			} else {
				$data['logo'] = 0;
			}
		}

		return parent::updateEntry($id, $data);
	}

	public function getCategoriesList() {
		return $this->_DP("List_Joined_Ep_BrandsCategories")->getList(null, null, array('languages_id' => self::$_user_language_id));
	}

	public function getCitiesList() {
		return $this->_DP("List_Joined_Ep_Cities")->getList(null, null, array('active'=>1, 'languages_id' => self::$_user_language_id), array("name" => "ASC"));
	}

}