<?php
class Db_Lang_SocialOrganizationsData extends Db_Lang {
	protected $_primary = 'id';
	protected $_name = 'social_organizations_data';
	protected $_name_expr = "name";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_SocialOrganizations',
		'Db_SocialOrganizations2Organizers',
	);

	protected $_referenceMap    = array(
		'Module' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_SocialOrganizations',
			'refColumns'        => 'id'
		)
	);
}