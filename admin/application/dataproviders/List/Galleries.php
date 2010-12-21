<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_Galleries extends List_Abstract {

	protected $_allowed_cols = array('id', 'active', 'name', 'thumbnail_create', 'thumbnail_height', 'thumbnail_width');

	protected $_db_table = "cms_galleries";

	protected $_select_list_cols = array('id', 'active', 'name', 'thumbnail_create', 'thumbnail_height', 'thumbnail_width');

}