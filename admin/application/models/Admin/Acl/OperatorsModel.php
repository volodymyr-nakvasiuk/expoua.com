<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Acl_OperatorsModel extends Admin_ListModelAbstract {

	protected $_DP_name = "List_Operators";

	protected $_DP_limit_params = array('type' => 'operator');

	public function getEntry($id) {
		$entry = parent::getEntry($id);

		$entry['countries'] = $this->_DP_obj->getOperatorCountries($id);
		return $entry;
	}

	public function updateEntry($id, Array $data) {
		$countries = array();
		if (isset($data['country']) && is_array($data['country'])) {
			$countries = $data['country'];
			unset($data['country']);
			$this->_DP_obj->updateOperatorCountries($id, $countries);
		}

		$res = parent::updateEntry($id, $data);

		return 1;
	}

	public function getCountriesList() {
		$list = $this->_DP("List_Joined_Ep_Countries")->getList(null, null, array('languages_id' => self::$_user_language_id), array("name" => "ASC"));

		return $list['data'];
	}

}