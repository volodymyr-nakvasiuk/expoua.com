<?php
class Db_LocationRegions extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'location_regions';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_Lang_LocationRegionsData',
		'Db_LocationCountries',
	);

	protected $_referenceMap    = array(
	);
}