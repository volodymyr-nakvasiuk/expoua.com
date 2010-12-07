<?php
class Db_Companies extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'companies';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_Lang_CompaniesData',
		'Db_Companies2BrandsCategories',
	);

	protected $_referenceMap    = array(
		'Module' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_Lang_CompaniesData',
			'refColumns'        => 'id'
		),
	);
}