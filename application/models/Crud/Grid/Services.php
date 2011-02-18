<?php
class Crud_Grid_Services extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "sort_order";
	public $direction = "DESC";
	public $editController = 'grid';
	public $editAction = 'services-tab';
	//protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Выставочный сервис';
		//$this->where = 'active=1';

		$this->gridActionName = 'services';
		$this->table = "Db_Services";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			//'languages_id' => new ArOn_Crud_Grid_Column_JoinOne("Язык выставки",array('Db_Lang_CompaniesData'),'languages_id',null,false,'200'),
			'description' => new ArOn_Crud_Grid_Column_JoinOne("Описание компании",array('Db_Lang_ServicesData'),'additional_info',null,false,'200'),
			//'brands_languages_id' => new ArOn_Crud_Grid_Column_JoinOne("Язык бренда",array('Db_Lang_BrandsData'),'languages_id',null,false,'200'),
			'name' => new ArOn_Crud_Grid_Column_JoinOne("Название компании",array('Db_Lang_ServicesData'),'name',null,false,'200'),
			'regions_id' => new ArOn_Crud_Grid_Column_JoinOne("Регион",array('Db_LocationCities','Db_LocationCountries'),'regions_id',null,false,'200'),
			'countries_id' => new ArOn_Crud_Grid_Column_JoinOne("Страна",array('Db_LocationCities'),'countries_id',null,false,'200'),
			'cities_id' => new ArOn_Crud_Grid_Column_Numeric("Город",null,true,false,'50'),
			//'active' => new ArOn_Crud_Grid_Column_Numeric('Активный',null,true,false,'50'),
			'logo' => new ArOn_Crud_Grid_Column_JoinOne("Описание компании",array('Db_Lang_ServicesData'),'logo',null,false,'200'),
			//'image' => new ArOn_Crud_Grid_Column_JoinOne("Описание компании",array('Db_Lang_ServicesData'),'image_logo',null,false,'200'),
		);
		//$this->fields['active']->options = array('1'=>"+", '0'=>"-");

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'search' => new ArOn_Crud_Grid_Filter_Field_Search('search','Язык бренда',array(
				array(
					'path' => array('Db_Lang_ServicesData'),
					'filters' => array(
						'name' => ArOn_Db_Filter_Search::LIKE,
					),
				),
			)),
			'region' => new ArOn_Crud_Grid_Filter_Field_Search('regions_id','Регион', array(
				array(
					'path' => array('Db_LocationCities','Db_LocationCountries'),
					'filters' => array(
						'regions_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'country' => new ArOn_Crud_Grid_Filter_Field_Search('countries_id','Страна',array(
				array(
					'path' => array('Db_LocationCities'),
					'filters' => array(
						'countries_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'city' => new ArOn_Crud_Grid_Filter_Field_Value('cities_id','Город',ArOn_Db_Filter_Field::EQ),
			'languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('languages_id','Язык выставки',array(
				array(
					'path' => array('Db_Lang_ServicesData'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'category' => new ArOn_Crud_Grid_Filter_Field_Value('service_companies_categories_id','Категория',ArOn_Db_Filter_Field::EQ),
			'active' => new ArOn_Crud_Grid_Filter_Field_Value('active','Активный',ArOn_Db_Filter_Field::EQ),
		);
		$this->_params['languages_id'] = DEFAULT_LANG_ID;
		$this->_params['active'] = 1;

		parent::init();
	}
}
