<?php
class Db_Lang_EventNewsData extends Db_Lang {
	protected $_primary = 'id';
	protected $_name = 'news_data';
	protected $_name_expr = "name";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_EventNews',
	);

	protected $_referenceMap    = array(
		//'Module' => array(
		//	'columns'           => 'id',
		//	'refTableClass'     => 'Db_EventNews',
		//	'refColumns'        => 'id'
		//)
	);
}
