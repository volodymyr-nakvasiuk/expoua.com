<?php
class Db_Events extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'events';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_Lang_EventsData',
		'Db_EventsCommon',
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
		'Expocenters' => array(
			'columns'           => 'expocenters_id',
			'refTableClass'     => 'Db_Expocenters',
			'refColumns'        => 'id'
		),
		'ExpocentersData' => array(
			'columns'           => 'expocenters_id',
			'refTableClass'     => 'Db_Lang_ExpocentersData',
			'refColumns'        => 'id'
		),
		'Module' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_Lang_EventsData',
			'refColumns'        => 'id'
		),
	);
}