<?php
class Db_Services extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'service_companies';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'sort_order';
	protected $_order_asc = false;

	protected $_dependentTables = array(
		'Db_Lang_ServicesData',
	);

	protected $_referenceMap    = array(
		'City' => array(
			'columns'           => 'cities_id',
			'refTableClass'     => 'Db_LocationCities',
			'refColumns'        => 'id'
		),
		'CityData' => array(
			'columns'           => 'cities_id',
			'refTableClass'     => 'Db_Lang_LocationCitiesData',
			'refColumns'        => 'id'
		),
		'Module' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_Lang_ServicesData',
			'refColumns'        => 'id'
		),
		'Category' => array(
			'columns'           => 'service_companies_categories_id',
			'refTableClass'     => 'Db_ServicesCategories',
			'refColumns'        => 'id'
		),
		'CategoryData' => array(
			'columns'           => 'service_companies_categories_id',
			'refTableClass'     => 'Db_Lang_ServicesCategoriesData',
			'refColumns'        => 'id'
		),
	);
}
