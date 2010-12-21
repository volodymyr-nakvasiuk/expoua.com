<?php
class Db_CompanyNews extends ArOn_Db_Table {
	protected $_primary = 'id';
	protected $_name = 'companies_news';
	protected $_name_expr = "id";
	protected $_is_deleted = false;
	protected $_order_expr = 'date_public';

	protected $_dependentTables = array(
		'Db_Lang_CompanyNewsData',
		'Db_Lang_CompanyNewsActive',
		//'Db_EventsCommon',
	);

	protected $_referenceMap    = array(
		'Country' => array(
			'columns'           => 'countries_id',
			'refTableClass'     => 'Db_LocationCountries',
			'refColumns'        => 'id'
		),
		'CountryData' => array(
			'columns'           => 'countries_id',
			'refTableClass'     => 'Db_Lang_LocationCountriesData',
			'refColumns'        => 'id'
		),
		'Companies' => array(
			'columns'           => 'companies_id',
			'refTableClass'     => 'Db_Companies',
			'refColumns'        => 'id'
		),
		'CompaniesData' => array(
			'columns'           => 'companies_id',
			'refTableClass'     => 'Db_Lang_CompaniesData',
			'refColumns'        => 'id'
		),
		'Module' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_Lang_CompanyNewsData',
			'refColumns'        => 'id'
		),
		'Active' => array(
			'columns'           => 'id',
			'refTableClass'     => 'Db_Lang_CompanyNewsActive',
			'refColumns'        => 'id'
		),
	);
}
