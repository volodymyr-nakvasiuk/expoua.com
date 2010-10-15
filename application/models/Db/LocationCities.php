<?php
class Db_LocationCities extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'location_cities';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_Lang_LocationCitiesData',
	);

	protected $_referenceMap    = array(
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
	);
}