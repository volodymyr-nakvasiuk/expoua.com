<?php
class Db_OnlineTypes extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'online_types';
	protected $_name_expr = "name";
	protected $_is_deleted = false;
	protected $_order_expr = 'name';

	protected $_dependentTables = array(
		'Db_OnlinePlaces',
	);

	protected $_referenceMap    = array(
	);
}
