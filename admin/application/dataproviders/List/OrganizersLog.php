<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_OrganizersLog extends List_Abstract {

	protected $_allowed_cols = array('id', 'event_time', 'type', 'description', 'users_operators_id');

	protected $_db_table = "ExpoPromoter_Opt.organizers_log";

	protected $_select_list_cols = array('id', 'event_time', 'type', 'description', 'users_operators_id');

	protected $_sort_col = array('event_time' => 'DESC');

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$select->join("ExpoPromoter_Opt.users_operators", "users_operators.id = log_operators.users_operators_id", array("operator_login" => "login"));
	}

	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		parent::_SqlAddsWhere($select, $params);

		if (isset($params['operator_login'])) {
			$select->where("users_operators.login ?", $params['operator_login']);
		}

		if (isset($params['date_from'])) {
			$select->where("log_operators.date_event ?", $params['date_from']);
		}
		if (isset($params['date_to'])) {
			$select->where("log_operators.date_event ?", $params['date_to']);
		}

	}

}