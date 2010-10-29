<?php
class Db_BrandsCategories extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'brands_categories';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_Lang_BrandsCategoriesData',
		'Db_BrandsSubcategories',
		'Db_Brands',
	);

	protected $_referenceMap    = array(
	);
}