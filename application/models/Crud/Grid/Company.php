<?php
class Crud_Grid_Company extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "date_modify";
	public $direction = "DESC";
	public $editController = 'grid';
	public $editAction = 'company-tab';
	protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Событие';
		//$this->where = 'active=1';

		$this->gridActionName = 'company';
		$this->table = "Db_Companies";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			//'languages_id' => new ArOn_Crud_Grid_Column_JoinOne("Язык выставки",array('Db_Lang_CompaniesData'),'languages_id',null,false,'200'),
			'description' => new ArOn_Crud_Grid_Column_JoinOne("Описание компании",array('Db_Lang_CompaniesData'),'description',null,false,'200'),
			//'brands_languages_id' => new ArOn_Crud_Grid_Column_JoinOne("Язык бренда",array('Db_Lang_BrandsData'),'languages_id',null,false,'200'),
			'name' => new ArOn_Crud_Grid_Column_JoinOne("Название компании",array('Db_Lang_CompaniesData'),'name',null,false,'200'),
			'regions_id' => new ArOn_Crud_Grid_Column_JoinOne("Регион",array('Db_LocationCities','Db_LocationCountries'),'regions_id',null,false,'200'),
			'countries_id' => new ArOn_Crud_Grid_Column_JoinOne("Страна",array('Db_LocationCities'),'countries_id',null,false,'200'),
			'cities_id' => new ArOn_Crud_Grid_Column_Numeric("Город",null,true,false,'50'),
			//'active' => new ArOn_Crud_Grid_Column_Numeric('Активный',null,true,false,'50'),
			'category' => new ArOn_Crud_Grid_Column_JoinOne('Категория', array('Db_Companies2Categories', 'Db_BrandsCategories'), null, array('id')),

			'email' => new ArOn_Crud_Grid_Column_Default("Дата до",null,true,false,'100'),
			'web_address' => new ArOn_Crud_Grid_Column_Default("Дата до",null,true,false,'100'),
			'email' => new ArOn_Crud_Grid_Column_Default("Дата до",null,true,false,'100'),
			'fax' => new ArOn_Crud_Grid_Column_Default("Дата до",null,true,false,'100'),
			'postcode' => new ArOn_Crud_Grid_Column_Default("Дата до",null,true,false,'100'),
			'address' => new ArOn_Crud_Grid_Column_JoinOne("Название компании",array('Db_Lang_CompaniesData'),'address',null,false,'200'),
			'logo' => new ArOn_Crud_Grid_Column_Numeric("Город",null,true,false,'50'),
		);
		//$this->fields['active']->options = array('1'=>"+", '0'=>"-");

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'id' => new ArOn_Crud_Grid_Filter_Field_Value('id','ID',ArOn_Db_Filter_Field::EQ),
			'languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('languages_id','Язык выставки',array(
				array(
					'path' => array('Db_Lang_CompaniesData'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
		);
		$this->_params['languages_id'] = DEFAULT_LANG_ID;

		parent::init();
	}
}
