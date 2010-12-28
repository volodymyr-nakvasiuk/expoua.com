<?php
class Db_Companies2 extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'companies';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_Lang_Companies2Data',
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
	);
}
