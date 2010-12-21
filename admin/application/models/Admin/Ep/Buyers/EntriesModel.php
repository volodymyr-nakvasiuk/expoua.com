<?PHP
Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Ep_Buyers_EntriesModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Buyers_Buyers';

	public function insertTransaction($id, Array $data) {
		return $this->_DP_obj->insertTransaction($id, $data);
	}
	
}