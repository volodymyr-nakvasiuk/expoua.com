<?php
class Db_SocialOrganizations2Organizers extends ArOn_Db_Table {
	
	protected $_primary = array('social_organizations_id','organizers_id');
	protected $_name = 'social_organizations_to_organizers';

	protected $_referenceMap    = array(
		'Organizers' => array(
			'columns'           => 'organizers_id',
			'refTableClass'     => 'Db_Organizers',
			'refColumns'        => 'id'
		),
		'OrganizersData' => array(
			'columns'           => 'organizers_id',
			'refTableClass'     => 'Db_Lang_OrganizersData',
			'refColumns'        => 'id'
		),
		'SocialOrganizations' => array(
			'columns'           => 'social_organizations_id',
			'refTableClass'     => 'Db_SocialOrganizations',
			'refColumns'        => 'id'
		),
		'SocialOrganizationsData' => array(
			'columns'           => 'social_organizations_id',
			'refTableClass'     => 'Db_Lang_SocialOrganizationsData',
			'refColumns'        => 'id'
		)
	);
}