<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Ep_OrganizersModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_Organizers';

	public function getEntry($id) {
		$entry = parent::getEntry($id);

		$entry['socorgs'] = $this->_DP_obj->getSelectedSocOrgsList($id);

		return $entry;
	}

	public function updateEntry($id, Array $data) {
    if (isset($data['web_address']) && !empty($data['web_address']) && strpos($data['web_address'], 'http://') === false) {
      $data['web_address'] = 'http://' . $data['web_address'];
    } 

		parent::updateEntry($id, $data);

		if (empty($data['so'])) {
			$data['so'] = array();
		}

		$this->_DP_obj->updateSocOrgs($id, $data['so']);

		return 1;
	}

	public function insertEntry(Array $data, $insertAllLangs = false) {
    if (isset($data['web_address']) && !empty($data['web_address']) && strpos($data['web_address'], 'http://') === false) {
      $data['web_address'] = 'http://' . $data['web_address'];
    } 

		$res = parent::insertEntry($data, true);

		if (!$res) {
			return $res;
		}

		if (empty($data['so'])) {
			$data['so'] = array();
		}

		$org_id = $this->_DP_obj->getLastInsertId();
		$this->_DP_obj->updateSocOrgs($org_id, $data['so']);

		return $res;
	}

	public function getSocialOrgsList() {
		$list = $this->_DP("List_Joined_Ep_Socorgs")->getList(null, null, array('languages_id' => self::$_user_language_id), array("name" => "ASC"));

		return $list['data'];
	}
}