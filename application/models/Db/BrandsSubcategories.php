<?php
class Db_BrandsSubcategories extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'brands_subcategories';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_Lang_BrandsSubcategories',
		'Db_Brands2Subcategories',
	);

	protected $_referenceMap    = array(
		'Category' => array(
			'columns'           => 'parent_id',
			'refTableClass'     => 'Db_BrandsCategories',
			'refColumns'        => 'id'
		),
		'CategoryData' => array(
			'columns'           => 'parent_id',
			'refTableClass'     => 'Db_Lang_BrandsCategoriesData',
			'refColumns'        => 'id'
		),
	);
}