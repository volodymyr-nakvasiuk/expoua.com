<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Acl_ResourcesModel extends Admin_ListModelAbstract {

	protected $_DP_name = "List_AclResources";

	public function getResourcesActionsList() {
		return $this->_DP($this->_DP_name)->getResourcesActionsList();
	}

	public function updateEntry($id, Array $data) {

		if (!isset($data['installed'])) {
			$data['installed'] = 0;
		}
		if (!isset($data['super'])) {
			$data['super'] = 0;
		}

		//Zend_Debug::dump($data);

		$actions = (array_key_exists('actions', $data) ? $data['actions']:array());
		unset($data['actions']);
		$this->_DP($this->_DP_name)->updateActions($id, $actions);

		parent::updateEntry($id, $data);

		return 1;
	}

	public function getEntry($id) {
		$data = parent::getEntry($id);
		$data['actions'] = $this->_DP($this->_DP_name)->getSelectedActions($id);

		return $data;
	}

}