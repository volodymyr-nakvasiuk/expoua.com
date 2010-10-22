<?php
class Db_Lang_LocationCitiesData extends Db_Lang {
	protected $_primary = 'id';
	protected $_name = 'location_cities_data';
	protected $_name_expr = "name";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_LocationCities',
	);

	protected $_referenceMap    = array(
		'Module' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_LocationCities',
			'refColumns'        => 'id'
		)
	);
}