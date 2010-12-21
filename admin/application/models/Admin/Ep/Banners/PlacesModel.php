<?PHP
require_once(PATH_MODELS . "/Admin/ListModelAbstract.php");

class Admin_Ep_Banners_PlacesModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Banners_Places';

	public function insertEntry(Array $data, $insertAllLangs = false) {
		$res = parent::insertEntry($data, $insertAllLangs);

		if ($res) {

			$id = $this->_DP_obj->getLastInsertId();

			if (!isset($data['types_id']) || !is_array($data['types_id'])) {
				$types = array();
			} else {
				$types = $data['types_id'];
			}

			$this->_DP_obj->updateSelectedTypes($id, $types);
		}

		return $res;
	}

	public function updateEntry($id, Array $data) {
		parent::updateEntry($id, $data);

		if (!isset($data['types_id']) || !is_array($data['types_id'])) {
			$types = array();
		} else {
			$types = $data['types_id'];
		}

		$this->_DP_obj->updateSelectedTypes($id, $types);

		return 1;
	}

	public function getEntry($id) {
		$res = parent::getEntry($id);

		$res['selected_types'] = $this->_DP_obj->getSelectedTypes($id);

		return $res;
	}

	public function getBannerTypesList() {
		$res = $this->_DP("List_Banners_Types")->getList();
		return $res['data'];
	}

}