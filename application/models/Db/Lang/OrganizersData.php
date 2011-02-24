<?php
class Db_Lang_OrganizersData extends Db_Lang {
	protected $_primary = 'id';
	protected $_name = 'organizers_data';
	protected $_name_expr = "name";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_Organizers',
		'Db_SocialOrganizations2Organizers',
		'Db_Brands',
	);

	protected $_referenceMap    = array(
		//'Module' => array(
		//	'columns'           => 'id',
		//	'refTableClass'     => 'Db_Organizers',
		//	'refColumns'        => 'id'
		//)
	);
}