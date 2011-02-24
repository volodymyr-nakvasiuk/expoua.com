<?php
class Db_ServicesGallery extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'service_companies_galleries';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
	);

	protected $_referenceMap    = array(
		'Services' => array(
			'columns'           => 'servcomps_id',
			'refTableClass'     => 'Db_Services',
			'refColumns'        => 'id'
		),
		'ServicesData' => array(
			'columns'           => 'servcomps_id',
			'refTableClass'     => 'Db_Lang_ServicesData',
			'refColumns'        => 'id'
		),
	);
}
