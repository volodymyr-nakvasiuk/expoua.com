<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Ep_RequestsModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Requests';

	public function getList($page = null, $parent = -1, $sort = null, $search = null) {

		if ($parent != -1) {
			$this->_DP_limit_params['type'] = $parent;
		}

		return parent::getList($page, $parent, $sort, $search);
	}

	public function getEntry($id) {
		$data = parent::getEntry($id);

		$data['details'] = $this->_DP_obj->getEntryData($id);

		return $data;
	}
}