<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Acl_Admins_GroupsModel extends Admin_ListModelAbstract {

	protected $_DP_name = "List_AclAdminsGroups";

	public function getFullList() {

		$extraParams = $this->_DP_limit_params;

		return $this->_DP_obj->getList(null, null, $extraParams);
	}

	public function getResourcesList($group_id) {
		$extraParams = array('super' => 1, 'installed' => 1);

		$resources_permissions = $this->_DP_obj->getAclResourcesList($group_id);

		$resources = $this->_DP("List_AclResources")->getList(null, null, $extraParams);
		$resources = $resources['data'];

		foreach ($resources as $key => $el) {

			if (array_key_exists($el['id'], $resources_permissions)) {
				$resources[$key]['allowed'] = true;
			} else {
				$resources[$key]['allowed'] = false;
			}

			$resources[$key]['actions'] = $this->_DP("List_AclResources")->getResourcesActionsList($el['id'], $group_id);

		}

		return $resources;
	}

	public function updateEntry($id, Array $data) {

		$resources = array();
		$actions = array();

		if (isset($data['resources_id']) && is_array($data['resources_id'])) {
			$resources = $data['resources_id'];
			unset($data['resources_id']);
		}

		if (isset($data['actions_id']) && is_array($data['actions_id'])) {
			$actions = $data['actions_id'];
			unset($data['actions_id']);
		}

		$res = parent::updateEntry($id, $data);

		$this->_DP_obj->updateAclResources($id, $resources);
		foreach ($resources as $el) {
			if (!array_key_exists($el, $actions) || !is_array($actions[$el])) {
				$actions[$el] = array();
			}
				$this->_DP_obj->updateAclRestrictedActions($id, $el, $actions[$el]);
		}

		return 1;
	}
}