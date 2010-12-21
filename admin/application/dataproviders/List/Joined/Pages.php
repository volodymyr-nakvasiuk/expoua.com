<?PHP
Zend_Loader::loadClass("List_Joined_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Pages extends List_Joined_Abstract {

	protected $_db_tables_array = array('cms_pages', 'cms_pages_data');

	protected $_db_tables_join_by = array(array('cms_pages.id', 'id'));

	protected $_allowed_cols_array = array(
		array('id', 'parent_id', 'active', 'templates_id'),
		array('id', 'languages_id', 'title', 'keywords', 'description', 'name', 'content')
	);

	protected $_select_list_cols_array = array(
		array('id', 'parent_id', 'active', 'templates_id'),
		array('name')
	);

	protected $_sort_col = array('id' => 'DESC');

	protected $_prepare_cols = array(
				'parent_id' => array('num', null),
				'templates_id' => array('num', null)
	);

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$select->joinLeft(
			"cms_templates",
			$this->_db_tables_array[0] . ".templates_id = cms_templates.id",
			array("template_name" => 'name')
		);

		parent::_SqlAddsList($select);
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		$select->joinLeft(
			"cms_templates",
			$this->_db_tables_array[0] . ".templates_id = cms_templates.id",
			array("template_name" => 'name')
		);

		parent::_SqlAddsEntry($select);
	}

}