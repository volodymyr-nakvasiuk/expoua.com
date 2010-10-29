<?php
class Db_EventsCommon extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'events_common';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
	);

	protected $_referenceMap    = array(
		'Periods' => array(
			'columns'           => 'periods_id',
			'refTableClass'     => 'Db_Periods',
			'refColumns'        => 'id'
		),
		'PeriodsData' => array(
			'columns'           => 'periods_id',
			'refTableClass'     => 'Db_Lang_PeriodsData',
			'refColumns'        => 'id'
		),
		'Module' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_Events',
			'refColumns'        => 'id'
		),
		'ModuleData' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_Lang_EventsData',
			'refColumns'        => 'id'
		),
	);
}