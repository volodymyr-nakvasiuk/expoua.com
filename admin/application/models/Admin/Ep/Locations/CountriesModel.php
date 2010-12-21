<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Ep_Locations_CountriesModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_Countries';

	public function getList($page = null, $parent = -1, $sort = null, $search = null) {

		if (!is_null($parent)) {
			$this->_DP_limit_params['regions_id'] = $parent;
		}

		return parent::getList($page, $parent, $sort, $search);
	}

	public function insertEntry(Array $data, $insertAllLangs = false) {

		$data['regions_id'] = $data['parent_id'];

		return parent::insertEntry($data, true);
	}
}