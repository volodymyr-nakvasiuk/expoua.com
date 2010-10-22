<?php
class Db_Lang_LocationCountriesData extends Db_Lang {
	protected $_primary = 'id';
	protected $_name = 'location_countries_data';
	protected $_name_expr = "name";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_LocationCities',
		'Db_LocationCountries',
	);

	protected $_referenceMap    = array(
		'Module' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_LocationCountries',
			'refColumns'        => 'id'
		)
	);
}