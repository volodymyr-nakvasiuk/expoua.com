<?php
class Db_Lang_Companies2Data extends Db_Lang {
	protected $_primary = 'id';
	protected $_name = 'companies_data';
	protected $_name_expr = "name";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
	);

	protected $_referenceMap    = array(
		'Module' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_Companies2',
			'refColumns'        => 'id'
		)
	);
}
