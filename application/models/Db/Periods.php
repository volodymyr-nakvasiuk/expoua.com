<?php
class Db_Periods extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'periods';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_Lang_PeriodsData',
	);

	protected $_referenceMap    = array(
	);
}