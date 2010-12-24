<?php
class Db_CompanyServices extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'companies_services';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'date_modify';

	protected $_dependentTables = array(
		'Db_Lang_CompanyServicesData',
		'Db_Lang_CompanyServicesActive',
		//'Db_EventsCommon',
	);

	protected $_referenceMap    = array(
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
		'Module' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_Lang_CompanyServicesData',
			'refColumns'        => 'id'
		),
		'Active' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_Lang_CompanyServicesActive',
			'refColumns'        => 'id'
		),
	);
}
