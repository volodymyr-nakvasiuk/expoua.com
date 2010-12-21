<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_AclAdmins extends List_Abstract {

	protected $_allowed_cols = array('id', 'active', 'login', 'passwd', 'name', 'added_by_uid', 'time_lastlogin', 'time_added');

	protected $_db_table = "acl_admin_users";

	protected $_select_list_cols = array('id', 'active', 'login', 'name', 'time_lastlogin');

	protected $_sort_col = array('id' => 'ASC');


	public function updateEntry($id = null, Array $data, Array $extraParams = array()) {
		if (array_key_exists("passwd", $data)) {
			$data['passwd'] = new Zend_Db_Expr(self::$_db->quoteInto("MD5(?)", $data['passwd']));
		}

		return parent::updateEntry($id, $data, $extraParams);
	}

	public function insertEntry(Array $data) {
		if (array_key_exists("passwd", $data)) {
			$data['passwd'] = new Zend_Db_Expr(self::$_db->quoteInto("MD5(?)", $data['passwd']));
		}

		return parent::insertEntry($data);
	}

	public function updateUserGroups($uid, Array $groups_ids) {
		$where = self::$_db->quoteInto("admin_users_id = ?", $uid);
		self::$_db->delete("acl_admin_users_to_groups", $where);

		$result = 0;
		foreach ($groups_ids as $el) {
			$row = array('admin_users_id' => $uid, 'admin_groups_id' => intval($el));
			$result += self::$_db->insert("acl_admin_users_to_groups", $row);
		}

		return $result;
	}

	/**
	 * Возвращает список, состоящий из id групп, в которые входит пользователь, id которого предано первым параметром
	 *
	 * @param int $uid
	 * @return array
	 */
	public function getUserGroupsList($uid) {
		$select = self::$_db->select();
		$select->from('acl_admin_users_to_groups', array('admin_groups_id', 'admin_groups_id'));
		$select->where("admin_users_id = ?", $uid);

		return self::$_db->fetchPairs($select);
	}

	/**
	 * Возвращает массив, содержащий названия групп, в которые входит пользователь, логин которого передан первым параметром
	 *
	 * @param string $userName
	 * @return array
	 */
	public function getUserGroupsNamesList($userName) {
		$select = self::$_db->select();
		$select->from('acl_admin_groups', array('id', 'parent_group_id', 'name'));
		$select->join('acl_admin_users_to_groups', 'acl_admin_groups.id = acl_admin_users_to_groups.admin_groups_id', array());
		$select->join('acl_admin_users', 'acl_admin_users.id = acl_admin_users_to_groups.admin_users_id', array());
		$select->where("acl_admin_users.login = ?", $userName);

		return self::$_db->fetchAssoc($select);
	}

	public function getUserIdByLogin($login) {
		$select = self::$_db->select();
		$select->from($this->_db_table, array('id'));
		$select->where("login = ?", $login);

		return self::$_db->fetchOne($select);
	}
}