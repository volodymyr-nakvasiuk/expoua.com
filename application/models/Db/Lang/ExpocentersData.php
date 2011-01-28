<?php
class Db_Lang_ExpocentersData extends Db_Lang {
	protected $_primary = 'id';
	protected $_name = 'expocenters_data';
	protected $_name_expr = "name";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_Expocenters',
	);

	protected $_referenceMap    = array(
		//'Module' => array(
		//	'columns'           => 'id',
		//	'refTableClass'     => 'Db_Expocenters',
		//	'refColumns'        => 'id'
		//)
	);
}