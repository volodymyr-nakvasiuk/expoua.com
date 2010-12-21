<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Acl_AdminsModel extends Admin_ListModelAbstract {

	protected $_DP_name = "List_AclAdmins";

	public function getGroupsList() {
		$groups = $this->_DP("List_AclAdminsGroups")->getList();
		return $groups['data'];
	}

	public function getList($page = null, $parent = -1, $sort = null, $search = null) {

		$list = parent::getList($page, $parent, $sort, $search);

		foreach ($list['data'] as $key=>$el) {
			$list['data'][$key]['groups'] = $this->_DP_obj->getUserGroupsList($key);
		}

		return $list;
	}

	public function getEntry($id) {
		$entry = parent::getEntry($id);

		$entry['groups'] = $this->_DP_obj->getUserGroupsList($id);
		return $entry;
	}

	public function updateEntry($id, Array $data) {
		$groups = array();
		if (isset($data['groups_ids']) && is_array($data['groups_ids'])) {
			$groups = $data['groups_ids'];
			unset($data['groups_ids']);
			$this->_DP_obj->updateUserGroups($id, $groups);
		}

		$res = parent::updateEntry($id, $data);

		return 1;
	}

}