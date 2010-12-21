<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_AclResources extends List_Abstract {

	protected $_allowed_cols = array('id', 'installed', 'super', 'code', 'description');

	protected $_db_table = "acl_resources";

	protected $_select_list_cols = array('id', 'installed', 'super', 'code', 'description');

	protected $_sort_col = array('code' => 'ASC');

	/**
	 * Возвращает список доступных и запрещенных действий для данного ресурса.
	 * Если группа не указана, зфпрещенные действия не отмечаются
	 * Если ресурс не указан, возвращаются все зарегистрированные действия
	 *
	 * @param int $resource_id
	 * @return array
	 */
	public function getResourcesActionsList($resource_id = null, $group_id = null) {
		$select = self::$_db->select();
		$select->from("acl_resources_actions");
		$select->order("code");

		if (!is_null($resource_id)) {
			$select->join("acl_resources_to_actions", "acl_resources_to_actions.actions_id = acl_resources_actions.id", array());
			$select->where("acl_resources_to_actions.resources_id = ?", $resource_id);

			if (!is_null($group_id)) {
				$select->joinLeft("acl_admin_resources_restricted_actions", "acl_admin_resources_restricted_actions.resources_id = acl_resources_to_actions.resources_id AND acl_admin_resources_restricted_actions.resources_actions_id = acl_resources_to_actions.actions_id AND " . self::$_db->quoteInto("acl_admin_resources_restricted_actions.admin_groups_id = ?", $group_id), array("restricted_action" => 'acl_admin_resources_restricted_actions.resources_actions_id'));
			}

		}

		//Zend_Debug::dump($select->__toString());

		return self::$_db->fetchAll($select);
	}

	public function updateActions($id, Array $actions) {
		$id = intval($id);

		$where = self::$_db->quoteInto("resources_id = ?", $id);
		self::$_db->delete("acl_resources_to_actions", $where);

		foreach ($actions as $key => $el) {
			$data = array('resources_id' => $id, 'actions_id' => intval($key));
			self::$_db->insert("acl_resources_to_actions", $data);
		}

		return true;
	}

	public function getSelectedActions($id) {
		$select = self::$_db->select();
		$select->from("acl_resources_to_actions", array("actions_id", 'actions_id'));
		$select->where("resources_id = ?", intval($id));

		return self::$_db->fetchPairs($select);
	}

}