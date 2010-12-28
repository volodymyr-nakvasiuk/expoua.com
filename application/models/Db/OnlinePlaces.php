<?php
class Db_OnlinePlaces extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'online_places';
	protected $_name_expr = "showrooms_order";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
	);

	protected $_referenceMap    = array(
		'Showrooms' => array(
			'columns'           => 'showrooms_id',
			'refTableClass'     => 'Db_OnlineShowRooms',
			'refColumns'        => 'id'
		),
		'Types' => array(
			'columns'           => 'types_id',
			'refTableClass'     => 'Db_OnlineTypes',
			'refColumns'        => 'id'
		),
		'Companies' => array(
			'columns'           => 'companies_id',
			'refTableClass'     => 'Db_Companies',
			'refColumns'        => 'id'
		),
		'CompaniesData' => array(
			'columns'           => 'companies_id',
			'refTableClass'     => 'Db_Lang_CompaniesData',
			'refColumns'        => 'id'
		),
	);
}
