<?php
class Db_Lang_LocationRegionsData extends Db_Lang {
	protected $_primary = 'id';
	protected $_name = 'location_regions_data';
	protected $_name_expr = "name";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_LocationCountries',
	);

	protected $_referenceMap    = array(
		'Module' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_LocationRegions',
			'refColumns'        => 'id'
		)
	);
}