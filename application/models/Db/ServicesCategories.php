<?php
class Db_ServicesCategories extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'service_companies_categories';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_Services',
		'Db_Lang_ServicesCategoriesData',
	);

	protected $_referenceMap    = array(
		//'Module' => array(
		//	'columns'           => 'id',
		//	'refTableClass'     => 'Db_Lang_ServicesCategoriesData',
		//	'refColumns'        => 'id'
		//),
	);
}
