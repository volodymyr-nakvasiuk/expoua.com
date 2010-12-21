<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Ep_Locations_RegionsModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_Regions';

	public function insertEntry(Array $data, $insertAllLangs = false) {
		return parent::insertEntry($data, true);
	}

}