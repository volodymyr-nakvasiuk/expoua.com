<?php
class Db_Lang_EventsData extends Db_Lang {
	protected $_primary = 'id';
	protected $_name = 'events_data';
	protected $_name_expr = "number";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_Brands',
		'Db_EventsCommon',
	);

	protected $_referenceMap    = array(
		'Module' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_Events',
			'refColumns'        => 'id'
		)
	);
}