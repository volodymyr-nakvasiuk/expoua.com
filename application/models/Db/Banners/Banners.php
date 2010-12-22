<?php
class Db_Banners_Banners extends Db_Banners {
	protected $_primary = 'id';
	protected $_name = 'banners';
	protected $_name_expr = "name";
	protected $_is_deleted = false;
	protected $_order_expr = 'id';

	protected $_dependentTables = array(
	);

	protected $_referenceMap    = array(
		//'Category' => array(
		//	'columns'           => 'brands_categories_id',
		//	'refTableClass'     => 'Db_BrandsCategories',
		//	'refColumns'        => 'id'
		//),
	);
}
