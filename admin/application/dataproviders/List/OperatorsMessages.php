<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_OperatorsMessages extends List_Abstract {

	protected $_allowed_cols = array('id', 'users_operators_id', 'message', 'answer');

	protected $_db_table = "ExpoPromoter_Opt.users_operators_messages";

	protected $_select_list_cols = array('id', 'users_operators_id', 'message', 'answer', 'date_posted');

	protected $_sort_col = array('id' => 'DESC');

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$select->join("ExpoPromoter_Opt.users_operators", "users_operators.id = users_operators_messages.users_operators_id", array('login', 'user_type' => 'type'));

		$select->joinLeft("ExpoPromoter_Opt.organizers", "organizers.id=users_operators.organizers_id", array());
		$select->joinLeft("ExpoPromoter_Opt.organizers_data", "organizers_data.id=organizers.id AND organizers_data.languages_id=1", array('organizer_name' => 'name'));
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
	}

	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		parent::_SqlAddsWhere($select, $params);

		if (isset($params['organizers_id'])) {
			if ($params['organizers_id'] instanceof Zend_Db_Expr) {
				$select->where("users_operators.organizers_id ?", $params['organizers_id']);
			} else {
				$select->where("users_operators.organizers_id = ?", $params['organizers_id']);
			}
		}
	}

}