<?php
class Db_Video_Videos extends Db_Video {
	protected $_primary = 'id';
	protected $_name = 'videos';
	protected $_name_expr = "title";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
	);

	protected $_referenceMap    = array(
		'Event' => array(
			'columns'           => 'id_expo_event',
			'refTableClass'     => 'Db_Events',
			'refColumns'        => 'id'
		),
		'EventData' => array(
			'columns'           => 'id_expo_event',
			'refTableClass'     => 'Db_Lang_EventsData',
			'refColumns'        => 'id'
		),
	);
}
