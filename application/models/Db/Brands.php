<?php
class Db_Brands extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'brands';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
		'Db_Lang_BrandsData',
		'Db_Events',
	);

	protected $_referenceMap    = array(
		'Category' => array(
			'columns'           => 'brands_categories_id',
			'refTableClass'     => 'Db_BrandsCategories',
			'refColumns'        => 'id'
		),
		'CategoryData' => array(
			'columns'           => 'brands_categories_id',
			'refTableClass'     => 'Db_Lang_BrandsCategoriesData',
			'refColumns'        => 'id'
		),
		'Organizer' => array(
			'columns'           => 'organizers_id',
			'refTableClass'     => 'Db_Organizers',
			'refColumns'        => 'id'
		),
		'OrganizerData' => array(
			'columns'           => 'organizers_id',
			'refTableClass'     => 'Db_Lang_OrganizersData',
			'refColumns'        => 'id'
		),
	);
}