<?php
class Db_CompaniesActive extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'companies_active';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_Companies'
	);

	protected $_referenceMap    = array(
		'Module' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_Companies',
			'refColumns'        => 'id'
		),
		'ModuleData' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_Lang_CompaniesData',
			'refColumns'        => 'id'
		),
	);
}