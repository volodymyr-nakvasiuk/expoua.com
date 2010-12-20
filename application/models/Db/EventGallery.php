<?php
class Db_EventGallery extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'events_galleries';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
	);

	protected $_referenceMap    = array(
		'Events' => array(
			'columns'           => 'events_id',
			'refTableClass'     => 'Db_Events',
			'refColumns'        => 'id'
		),
		'EventsData' => array(
			'columns'           => 'events_id',
			'refTableClass'     => 'Db_Lang_EventsData',
			'refColumns'        => 'id'
		),
	);
}
