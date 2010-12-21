<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_Buyers_Registrations extends List_Abstract {

	protected $_allowed_cols = array('id_registration', 'id_affiliate', 'id_form', 'date',
		'firstname', 'lastname', 'email', 'fields');
	protected $_allowed_cols_cancellations = array('id_registration', 'reason', 'approve_status');

	protected $_db_table = "tickets.registrations";

	protected $_select_list_cols = array('id_registration', 'date', 'firstname', 'lastname');

	protected $_sort_col = array('id_registration' => 'DESC');

	/**
	 * Без комментариев, не знаю как оно работает
	 * Но возвращает форму
	 * 
	 * @return array
	 */
	public function getEntry($ticket_id, Array $extraParams = array()) {
		$lang = Zend_Registry::get("language_code");
		$ticket = self::$_db-> fetchRow("SELECT r.*, c.approve_status, c.reason, f.id_event
FROM tickets.registrations AS r
INNER JOIN tickets.forms AS f ON f.id_form=r.id_form
LEFT JOIN tickets.registrations_cancellations AS c ON c.id_registration=r.id_registration
WHERE r.id_registration = ?", array($ticket_id));

		$ticket['fields'] = json_decode($ticket['fields']);
		$fields = $this->_getFieldsList($ticket['id_event']);
		$ticket['fieldClasses'] = $fields;
		$ticket['namedValues'] = array();
		foreach ($fields as $field) {
			if (strpos($field['type'], 'default_') === 0) {
				$value = $ticket[substr($field['type'], 8)];
			} elseif (isset($ticket['fields']->{$field['id_field']})) {
				$value = $ticket['fields']->{$field['id_field']};
			} else {
				$value='';
			}
			if (is_object($value)) {
				if (isset($value->$lang)) {
					$value = $value->$lang;
				} else {
					$vars = array_values(get_object_vars($value));
					$value = $vars[0];
				}
			}
			$ticket['namedValues'][$field['name']] = $value;
		}
		return $ticket;
	}
	
	private function _getFieldsList($events_id) {
		$lang = Zend_Registry::get("language_code");
		$fields_plain = self::$_db-> fetchAll("SELECT ff.*, ffd.*
FROM  tickets.forms AS f
INNER JOIN tickets.forms_fields AS ff ON ff.id_form=f.id_form
INNER JOIN tickets.forms_fields_data AS ffd ON ffd.id_field=ff.id_field AND ffd.id_language = ?
INNER JOIN tickets.forms_fields_positions AS ffp ON ffp.id_field=ff.id_field
WHERE f.id_event = ? ORDER BY ffp.position", array($lang, $events_id));
		$fields = array();
		foreach ($fields_plain as $field) {
			$field['config'] = json_decode($field['config']);
			$fields[$field['id_field']] = $field;
		}
		return $fields;
	}

	public function updateEntry($id = null, Array $data, Array $extraParams = array()) {
		$res1 = $this->_updateEntry($id, $data, $extraParams);

		$tmp_table = $this->_db_table;
		$tmp_allowed_cols = $this->_allowed_cols;

		$this->_db_table = "tickets.registrations_cancellations";
		$this->_allowed_cols = $this->_allowed_cols_cancellations;

		$res2 = $this->_updateEntry($id, $data, $extraParams);

		$this->_db_table = $tmp_table;
		$this->_allowed_cols = $tmp_allowed_cols;

		return max($res1, $res2);
	}

	private function _updateEntry($id = null, Array $data, Array $extraParams = array()) {
		$this->_prepareDataArray($data);

		if (sizeof($data) == 0) {
			return 0;
		}

		$where = array();

		if (!is_null($id)) {
			$where[] = self::$_db->quoteInto("id_registration = ?", intval($id));
		}

		if (sizeof($extraParams) > 0) {
			$this->_prepareDataArray($extraParams);
			foreach ($extraParams as $key => $el) {
				$where[] = self::$_db->quoteInto($key . " = ?", $el);
			}
		}

		try {
			$res = self::$_db->update($this->_db_table, $data, $where);
		} catch (Exception $e) {
			echo $e->getMessage();
		}

		return $res;
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {

	}

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$select->join("tickets.forms", "registrations.id_form = forms.id_form", array());
		$select->join("ExpoPromoter_Opt.buyers", "buyers.id = forms.id_event", array());
		$select->join("ExpoPromoter_Opt.events", "events.id = buyers.events_id",
			array('events_id' => 'id'));
		$select->join("ExpoPromoter_Opt.brands_data", "brands_data.id = events.brands_id",
			array('brand_name' => 'name'));

		$select->joinLeft("tickets.registrations_cancellations",
			"registrations.id_registration = registrations_cancellations.id_registration",
			array(
				'cancellation_reason' => 'reason',
				'cancellation_approve_status' => 'approve_status'
			)
		);
					
		$select->where("brands_data.languages_id = ?", Zend_Registry::get("language_id"));
	}

	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		parent::_SqlAddsWhere($select, $params);

		if (isset($params['cancellation_approve_status'])) {
			if ($params['cancellation_approve_status'] instanceof Zend_Db_Expr) {
				$select->where("registrations_cancellations.approve_status ?",
				$params['cancellation_approve_status']);
			} else {
				$select->where("registrations_cancellations.approve_status = ?",
				$params['cancellation_approve_status']);
			}
		}
	}
}