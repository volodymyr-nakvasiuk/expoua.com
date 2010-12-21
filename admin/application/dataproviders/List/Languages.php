<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_Languages extends List_Abstract {

	protected $_allowed_cols = array('id', 'active', 'code', 'name');

	protected $_db_table = "cms_languages";

	protected $_select_list_cols = array('id', 'code', 'name');
	
	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		parent::_SqlAddsWhere($select, $params);

		$select->where("active = 1");
	}
	
}