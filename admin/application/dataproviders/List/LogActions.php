<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_LogActions extends List_Abstract {

	protected $_allowed_cols = array('id', 'date_event', 'acl_resources_id',
		'acl_resources_actions_id', 'acl_admin_users_id', 'users_operators_id', 'param_id');

	protected $_db_table = "log_actions";

	protected $_select_list_cols = array('id', 'date_event', 'acl_resources_id',
		'acl_resources_actions_id', 'acl_admin_users_id', 'param_id');

	protected $_sort_col = array('id' => 'DESC');


	public function getList($results_num = null, $page = null, Array $extraParams = array(), Array $sortBy = array()) {
		$result = array();

		$select_sq = self::$_db->select();

		$select_sq->from($this->_db_table);

		//В случае наличия дополнительных ограничивающих параметров, учитываем их
		if (sizeof($extraParams) >0) {
			$this->_SqlAddsWhere($select_sq, $extraParams);
		}

		//Если нужен пейджинг, вводим ограничения
		if (!is_null($results_num) && !is_null($page)) {
			$page = intval($page);
			$results_num = intval($results_num);

			//Определяемся с общим количеством записей в таблице
			$select_count = clone $select_sq;

			$select_count->reset(Zend_Db_Select::COLUMNS);
			$select_count->from('', new Zend_Db_Expr("COUNT(*)"));
			//Zend_Debug::dump($select_count->__toString());

			$number_of_rows = self::$_db->fetchOne($select_count);
			$number_of_pages = ceil($number_of_rows / $results_num);

			if ($page > $number_of_pages) {
				$page = $number_of_pages;
			}

			$result = array('page' => $page, 'pages' => $number_of_pages, 'rows' => $number_of_rows);

			$select_sq->limitPage($page, $results_num);
		} else {
			$result = array('page' => 1, 'pages' => 1, 'rows' => 0);
		}

		$result['sort_by'] = null;

		//Если нужно, сортируем результат
		if (sizeof($sortBy)>0) {
			$result['sort_by'] = $this->_SqlAddsSort($select_sq, $sortBy);
		}

		if (is_null($result['sort_by']) && sizeof($this->_sort_col) > 0) {
			$result['sort_by'][key($this->_sort_col)] = current($this->_sort_col);
			foreach ($this->_sort_col as $key => $el) {
				$select_sq->order($this->_db_table . "." . $key . " " . $el);
			}
		}

		$select = self::$_db->select();

		$select->from(array('sq' => $select_sq));

		$this->_SqlAddsList($select);

		$this->_SqlAddsDebug($select);

		try {
			$result['data'] = self::$_db->fetchAssoc($select);
		} catch (Exception $e) {
			echo $e->getMessage();
		}

		return $result;
	}

	public function insertEntry(Array $data) {
		//Zend_Debug::dump($data);

		$select = self::$_db->select();
		$select->from("acl_resources", array('id',
		new Zend_Db_Expr((is_null($data['param_id']) ? 'NULL':self::$_db->quote($data['param_id']))),
		new Zend_Db_Expr(self::$_db->quoteInto("INET_ATON(?)", $_SERVER['REMOTE_ADDR']))));

		$select->joinLeft("acl_resources_actions", self::$_db->quoteInto("acl_resources_actions.code = ?", $data['action']), array('id'));

		if (isset($data['admin_identity'])) {
			$select->joinLeft("acl_admin_users", self::$_db->quoteInto("acl_admin_users.login = ?", $data['admin_identity']), array('id'));
		}

		if (isset($data['operator_identity'])) {
			$select->joinLeft("ExpoPromoter_Opt.users_operators", self::$_db->quoteInto("users_operators.login = ?", $data['operator_identity']), array('id'));
		}

		$select->where("acl_resources.code = ?", $data['controller']);

		$query = "INSERT INTO log_actions (acl_resources_id, param_id, user_ip, acl_resources_actions_id" . (isset($data['admin_identity']) ? ", acl_admin_users_id":"") . (isset($data['operator_identity']) ? ", users_operators_id":"") . ")\n" . $select->__toString();

		//Zend_Debug::dump($query);

		try {
			self::$_db->query($query);
		} catch (Exception $e) {
			echo $e->getMessage();
			return 0;
		}

		return 1;
	}

	public function updateEntry($id = null, Array $data, Array $extraParams = array()) {
		//Ничего не делаем
	}

	public function deleteEntry(Array $id = array(), Array $extraParams = array()) {
		//Ничего не делаем
	}

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$select->join("acl_resources", "acl_resources.id = sq.acl_resources_id", array("code_resource" => "code"));
		$select->joinLeft("acl_resources_actions", "acl_resources_actions.id = sq.acl_resources_actions_id", array('code_action' => 'code'));
		$select->joinLeft("acl_admin_users", "acl_admin_users.id = sq.acl_admin_users_id", array('admin_login' => 'login'));
		$select->joinLeft("ExpoPromoter_Opt.users_operators", "users_operators.id = sq.users_operators_id", array('operator_login' => 'login'));

		//echo $select->__toString();
	}

	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		parent::_SqlAddsWhere($select, $params);

		if (isset($params['date_from'])) {
			$select->where("date_event ?", $params['date_from']);
		}
		if (isset($params['date_to'])) {
			$select->where("date_event ?", $params['date_to']);
		}
	}

	protected function _SqlAddsDebug(Zend_Db_Select &$select) {
		//echo $select->__toString();
	}

}