<?php
class Db_Lang_BrandsSubcategoriesData extends Db_Lang {
	protected $_primary = 'id';
	protected $_name = 'brands_subcategories_data';
	protected $_name_expr = "name";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_BrandsSubcategories',
	);

	protected $_referenceMap    = array(
		'Module' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_BrandsSubcategories',
			'refColumns'        => 'id'
		)
	);
}