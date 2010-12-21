<?PHP
Zend_Loader::loadClass("List_Abstract", PATH_DATAPROVIDERS);

class List_NewsCategories extends List_Abstract {

	protected $_allowed_cols = array('id', 'languages_id', 'name');

	protected $_db_table = "cms_news_categories";

	protected $_select_list_cols = array('id', 'name');

}