<?php
class Db_Companies2Categories2 extends ArOn_Db_Table {
	
	protected $_primary = array('brands_categories_id','companies_id');
	protected $_name = 'companies_to_brands_categories';

	protected $_dependentTables = array(
		'Db_Companies',
		'Db_Lang_CompaniesData',
		'Db_BrandsCategories',
		'Db_Lang_BrandsCategoriesData',
	);


	protected $_referenceMap    = array(
		/*
		'Companies' => array(
			'columns'           => 'companies_id',
			'refTableClass'     => 'Db_Companies',
			'refColumns'        => 'id'
		),
		'CompaniesData' => array(
			'columns'           => 'companies_id',
			'refTableClass'     => 'Db_Lang_CompaniesData',
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
		*/
	);
}