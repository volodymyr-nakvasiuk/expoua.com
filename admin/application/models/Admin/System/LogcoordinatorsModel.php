<?PHP
Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_System_LogcoordinatorsModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_LogActions';

	protected $_DP_limit_params = array(
		'acl_resources_id' => array(30, 61, 29, 28, 32)
	);
	
	public function getList($page = null, $parent = -1, $sort = null, $search = null) {
		$dp_save = $this->_DP_limit_params;
		if (is_array($search)) {
			$search_tmp = $this->_prepareSearchConditions($search);
			if (isset($search_tmp['acl_resources_id'])) {
				unset($this->_DP_limit_params['acl_resources_id']);
			}
		}
		$res = parent::getList($page, $parent, $sort, $search);
		
		$this->_DP_limit_params = $dp_save;
		return $res;
	}
	
	public function getResourcesList() {
		$extraParams = array('id' => $this->_DP_limit_params['acl_resources_id']);
		$res = $this->_DP("List_AclResources")->getList(null, null, $extraParams);
		return $res['data'];
	}
	
	public function getUsersList() {
		$extraParams = array('active' => 1);
		$res = $this->_DP("List_AclAdmins")->getList(null, null, $extraParams);
		return $res['data'];
	}
	
}