<?php
class Db_Lang_PeriodsData extends Db_Lang {
	protected $_primary = 'id';
	protected $_name = 'periods_data';
	protected $_name_expr = "name";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_Periods',
	);

	protected $_referenceMap    = array(
		'Module' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_Periods',
			'refColumns'        => 'id'
		)
	);
}