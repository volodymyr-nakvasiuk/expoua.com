<?php
class Db_Lang_BrandsCategoriesData extends Db_Lang {
	protected $_primary = 'id';
	protected $_name = 'brands_categories_data';
	protected $_name_expr = "name";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_BrandsCategories',
		'Db_BrandsSubcategories',
		'Db_Brands',
		'Db_OnlineShowrooms',
	);

	protected $_referenceMap    = array(
		'Module' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_BrandsCategories',
			'refColumns'        => 'id'
		)
	);
}