<?php
class Db_OnlineShowRooms extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'online_showrooms';
	protected $_name_expr = "name";
	protected $_is_deleted = false;
	protected $_order_expr = 'order';

	protected $_dependentTables = array(
		'Db_OnlinePlaces',
	);

	protected $_referenceMap    = array(
		'Categories' => array(
			'columns'           => 'brands_categories_id',
			'refTableClass'     => 'Db_BrandsCategories',
			'refColumns'        => 'id'
		),
		'CategoriesData' => array(
			'columns'           => 'brands_categories_id',
			'refTableClass'     => 'Db_Lang_BrandsCategoriesData',
			'refColumns'        => 'id'
		),
	);
}
