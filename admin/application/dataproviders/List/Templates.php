<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_Templates extends List_Abstract {

	protected $_allowed_cols = array('id', 'tpl_cat_id', 'name', 'description', 'content');

	protected $_db_table = "cms_templates";

	protected $_select_list_cols = array('id', 'name', 'description');

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$select->join("cms_template_categories", $this->_db_table . ".tpl_cat_id = cms_template_categories.id", array("name_category" => "name"));
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		$select->join("cms_template_categories", $this->_db_table . ".tpl_cat_id = cms_template_categories.id", array("name_category" => "name"));
	}

}