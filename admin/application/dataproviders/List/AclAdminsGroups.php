<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_AclAdminsGroups extends List_Abstract {

	protected $_allowed_cols = array('id', 'parent_group_id', 'name', 'description');

	protected $_db_table = "acl_admin_groups";

	protected $_select_list_cols = array('id', 'parent_group_id', 'name', 'description');

	protected $_sort_col = array('id' => 'ASC');

	protected $_prepare_cols = array(
				'parent_group_id' => array('num', null)
	);

	public function updateAclResources($group_id, Array $resources) {
		$where = self::$_db->quoteInto("admin_groups_id = ?", $group_id);
		self::$_db->delete("acl_admin_groups_to_resources", $where);

		$result = 0;

		foreach ($resources as $el) {
			$row = array('admin_groups_id' => $group_id, 'resources_id' => intval($el));
			$result += self::$_db->insert("acl_admin_groups_to_resources", $row);
		}

		return $result;
	}

	public function updateAclRestrictedActions($group_id, $resource_id, Array $actions) {
		$where = array(
			self::$_db->quoteInto("admin_groups_id = ?", $group_id),
			self::$_db->quoteInto("resources_id = ?", $resource_id)
		);
		self::$_db->delete("acl_admin_resources_restricted_actions", $where);

		$result = 0;

		foreach ($actions as $el) {
			$row = array('admin_groups_id' => $group_id, 'resources_id' => $resource_id, 'resources_actions_id' => intval($el));

			$result += self::$_db->insert("acl_admin_resources_restricted_actions", $row);
		}

		return $result;
	}

	public function getAclResourcesList($group_id) {
		$select = self::$_db->select();
		$select->from("acl_admin_groups_to_resources", array("resources_id", "resources_id"));
		$select->where("admin_groups_id = ?", $group_id);

		return self::$_db->fetchPairs($select);
	}
}