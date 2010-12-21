<?PHP

Zend_Loader::loadClass("List_Joined_Abstract", PATH_DATAPROVIDERS);

class List_Joined_GalleriesElements extends List_Joined_Abstract {

	protected $_db_tables_array = array('cms_galleries_items', 'cms_galleries_items_data');

	protected $_db_tables_join_by = array(array('cms_galleries_items.id', 'id'));

	protected $_allowed_cols_array = array(array('id', 'parent_id', 'active', 'image_height', 'image_width', 'filename'), array('id', 'languages_id', 'alt', 'name', 'description'));

	protected $_select_list_cols_array = array(array('id', 'parent_id', 'active', 'image_height', 'image_width', 'filename'), array('alt', 'name'));

	protected $_sort_col = array('id' => 'DESC');

}