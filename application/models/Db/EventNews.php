<?php
class Db_EventNews extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'news';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'date_public';

	protected $_dependentTables = array(
		'Db_Lang_EventNewsData',
		//'Db_EventsCommon',
	);

	protected $_referenceMap    = array(
		'Events' => array(
			'columns'           => 'events_id',
			'refTableClass'     => 'Db_Events',
			'refColumns'        => 'id'
		),
		'EventsData' => array(
			'columns'           => 'events_id',
			'refTableClass'     => 'Db_Lang_EventsData',
			'refColumns'        => 'id'
		),
		'Country' => array(
			'columns'           => 'countries_id',
			'refTableClass'     => 'Db_LocationCountries',
			'refColumns'        => 'id'
		),
		'CountryData' => array(
			'columns'           => 'countries_id',
			'refTableClass'     => 'Db_Lang_LocationCountriesData',
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
			'refTableClass'     => 'Db_Lang_EventNewsData',
			'refColumns'        => 'id'
		),
	);
}
