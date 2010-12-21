<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Ep_Locations_CitiesModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_Cities';

	protected $_DP_limit_params = array('extended' => 0);

	public function getList($page = null, $parent = -1, $sort = null, $search = null) {

		if (!is_null($parent)) {
			$this->_DP_limit_params['countries_id'] = $parent;
		}

		return parent::getList($page, $parent, $sort, $search);
	}

	public function insertEntry(Array $data, $insertAllLangs = false) {

		$data['countries_id'] = $data['parent_id'];

		$res = parent::insertEntry($data, true);

		if ($res == 1) {
			$id = $this->_DP_obj->getLastInsertId();
			$this->_DP_obj->findCityInGeoDB($id);
		}

		return $res;
	}
	
	public function updateEntry($id, Array $data) {
		$res = parent::updateEntry($id, $data);
		
		if ($res == 1) {
			$this->_DP_obj->findCityInGeoDB($id);
		}
		
		return $res;
	}
}