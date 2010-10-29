<?php
class Db_Brands2Subcategories extends ArOn_Db_Table {
	
	protected $_primary = array('brands_id','subcategories_id');
	protected $_name = 'brands_to_subcategories';

	protected $_referenceMap    = array(
		'Brands' => array(
			'columns'           => 'brands_id',
			'refTableClass'     => 'Db_Brands',
			'refColumns'        => 'id'
		),
		'BrandsData' => array(
			'columns'           => 'brands_id',
			'refTableClass'     => 'Db_Lang_BrandsData',
			'refColumns'        => 'id'
		),
		'BrandsSubcategories' => array(
			'columns'           => 'subcategories_id',
			'refTableClass'     => 'Db_BrandsSubcategories',
			'refColumns'        => 'id'
		),
		'BrandsSubcategoriesData' => array(
			'columns'           => 'subcategories_id',
			'refTableClass'     => 'Db_Lang_BrandsSubcategoriesData',
			'refColumns'        => 'id'
		)
	);
}