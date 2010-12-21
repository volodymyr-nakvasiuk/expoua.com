<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_TemplatesCategories extends List_Abstract {

	protected $_allowed_cols = array('id', 'name', 'description');

	protected $_db_table = "cms_template_categories";

	protected $_select_list_cols = array('id', 'name', 'description');

}