<?php
class Db_User extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'users_companies';
	protected $_name_expr = "name";
	//protected $_is_deleted = "is_deleted";
	protected $_aclWhere = "%s";
	protected $_aclColumn = 'id';
	
	protected $_dependentTables = array(
	);
	protected $_referenceMap    = array(
	);
}