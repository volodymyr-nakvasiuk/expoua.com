<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_System_LogoperatorsModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_LogOperators';

	public function getOperatorsList() {
		$res = $this->_DP("List_Operators")->getList(null, null, array('type' => 'operator'));

		return $res['data'];
	}

}