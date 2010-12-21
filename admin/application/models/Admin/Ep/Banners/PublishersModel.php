<?PHP
require_once(PATH_MODELS . "/Admin/ListModelAbstract.php");

class Admin_Ep_Banners_PublishersModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Banners_Publishers';

	public function insertEntry(Array $data, $insertAllLangs = false) {
		$res = parent::insertEntry($data, $insertAllLangs);

		if ($res) {

			$id = $this->_DP_obj->getLastInsertId();

			if (!isset($data['places_id']) || !is_array($data['places_id'])) {
				$places = array();
			} else {
				$places = $data['places_id'];
			}
			if (!isset($data['languages_id']) || !is_array($data['languages_id'])) {
				$languages = array();
			} else {
				$languages = $data['languages_id'];
			}

			$this->_DP_obj->updateSelectedPlaces($id, $places);
			$this->_DP_obj->updateSelectedLanguages($id, $languages);
		}

		return $res;
	}

	public function updateEntry($id, Array $data) {
		parent::updateEntry($id, $data);

		if (!isset($data['places_id']) || !is_array($data['places_id'])) {
			$places = array();
		} else {
			$places = $data['places_id'];
		}
		if (!isset($data['languages_id']) || !is_array($data['languages_id'])) {
			$languages = array();
		} else {
			$languages = $data['languages_id'];
		}

		$this->_DP_obj->updateSelectedPlaces($id, $places);
		$this->_DP_obj->updateSelectedLanguages($id, $languages);

		return 1;
	}

	public function getEntry($id) {
		$res = parent::getEntry($id);

		$res['selected_places'] = $this->_DP_obj->getSelectedPlaces($id);
		$res['selected_languages'] = $this->_DP_obj->getSelectedLanguages($id);

		return $res;
	}


	public function getBannerPlacesList() {
		$res = $this->_DP("List_Banners_Places")->getList();

		return $res['data'];
	}
}