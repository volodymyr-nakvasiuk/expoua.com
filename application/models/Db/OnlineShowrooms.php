<?php
class Db_OnlineShowrooms extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'online_showrooms';
	protected $_name_expr = "name";
	protected $_is_deleted = false;
	protected $_order_expr = 'order';

	protected $_dependentTables = array(
		'Db_OnlinePlaces',
	);

	protected $_referenceMap    = array(
	);
}
