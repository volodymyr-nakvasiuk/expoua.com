<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_LogOperators extends List_Abstract {

	protected $_allowed_cols = array('id', 'date_event', 'users_operators_id', 'acl_admin_users_id', 'type', 'events_id');

	protected $_db_table = "ExpoPromoter_Opt.log_operators";

	protected $_select_list_cols = array('id', 'date_event', 'users_operators_id', 'acl_admin_users_id', 'type', 'events_id');

	protected $_sort_col = array('id' => 'DESC');

	protected $_prepare_cols = array(
				'events_id' => array('num', null),
				'acl_admin_users_id' => array('num', 0)
	);

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$select->join("ExpoPromoter_Opt.users_operators", "users_operators.id = log_operators.users_operators_id", array("operator_login" => "login"));
		$select->joinLeft("ExpoPromoter_cms.acl_admin_users", "acl_admin_users.id = log_operators.acl_admin_users_id", array('admin_login' => 'login'));
	}

	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		parent::_SqlAddsWhere($select, $params);

		if (isset($params['operator_login'])) {
			$select->where("users_operators.login ?", $params['operator_login']);
		}
		if (isset($params['admin_login'])) {
			$select->where("acl_admin_users.login ?", $params['admin_login']);
		}

		if (isset($params['date_from'])) {
			$select->where("log_operators.date_event ?", $params['date_from']);
		}
		if (isset($params['date_to'])) {
			$select->where("log_operators.date_event ?", $params['date_to']);
		}

	}

}