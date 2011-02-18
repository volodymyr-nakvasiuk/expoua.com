<?php
class Db_Lang_ServicesData extends Db_Lang {
	protected $_primary = 'id';
	protected $_name = 'service_companies_data';
	protected $_name_expr = "name";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_Services',
	);

	protected $_referenceMap    = array(
		//'Module' => array(
		//	'columns'           => 'id',
		//	'refTableClass'     => 'Db_Services',
		//	'refColumns'        => 'id'
		//)
	);
}
