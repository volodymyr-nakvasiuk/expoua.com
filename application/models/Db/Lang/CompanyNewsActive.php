<?php
class Db_Lang_CompanyNewsActive extends Db_Lang {
	protected $_primary = 'id';
	protected $_name = 'companies_news_active';
	protected $_name_expr = "active";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_CompanyNews',
	);

	protected $_referenceMap    = array(
		//'Module' => array(
		//	'columns'           => 'id',
		//	'refTableClass'     => 'Db_EventNews',
		//	'refColumns'        => 'id'
		//)
	);
}
