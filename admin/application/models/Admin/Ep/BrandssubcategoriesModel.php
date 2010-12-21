<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Ep_BrandssubcategoriesModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_BrandsSubCategories';

	public function insertEntry(Array $data, $insertAllLangs = false) {
		return parent::insertEntry($data, true);
	}

}