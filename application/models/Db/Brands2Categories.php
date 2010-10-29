<?php
class Db_Brands2Categories extends ArOn_Db_Table {
	
	protected $_primary = array('brands_id','brands_categories_id');
	protected $_name = 'brands_to_categories';

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
		'BrandsCategories' => array(
			'columns'           => 'brands_categories_id',
			'refTableClass'     => 'Db_BrandsCategories',
			'refColumns'        => 'id'
		),
		'BrandsCategoriesData' => array(
			'columns'           => 'brands_categories_id',
			'refTableClass'     => 'Db_Lang_BrandsCategoriesData',
			'refColumns'        => 'id'
		)
	);
}