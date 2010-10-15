<?php
class Db_Languages extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'languages';
	protected $_name_expr = "name";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
	);

	protected $_referenceMap    = array(
	);

}