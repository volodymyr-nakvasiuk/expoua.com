<?php
class Db_LocationCountries extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'location_countries';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_Lang_LocationCountriesData',
		'Db_LocationCities',
	);

	protected $_referenceMap    = array(
		'Region' => array(
			'columns'           => 'regions_id',
			'refTableClass'     => 'Db_LocationRegions',
			'refColumns'        => 'id'
		),
		'RegionData' => array(
			'columns'           => 'regions_id',
			'refTableClass'     => 'Db_Lang_LocationRegionsData',
			'refColumns'        => 'id'
		),
	);
}