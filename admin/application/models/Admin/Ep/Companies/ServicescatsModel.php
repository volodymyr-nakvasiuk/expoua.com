<?PHP

Zend_Loader::loadClass("Admin_Ep_Companies_ModelAbstract", PATH_MODELS);

class Admin_Ep_Companies_ServicescatsModel extends Admin_Ep_Companies_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_CompaniesServicesCats';

	public function insertEntry(Array $data, $insertAllLangs = false) {
		return parent::insertEntry($data, true);
	}

}