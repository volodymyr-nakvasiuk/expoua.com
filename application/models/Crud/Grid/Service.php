<?php
class Crud_Grid_Service extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "id";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'services-tab';
	protected $_ifCount = false;

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

			'address' => new ArOn_Crud_Grid_Column_JoinOne("Адрес",array('Db_Lang_ServicesData'),'address',null,false,'200'),
			'postcode' => new ArOn_Crud_Grid_Column_JoinOne("Индекс",array('Db_Lang_ServicesData'),'postcode',null,false,'200'),
			'phone' => new ArOn_Crud_Grid_Column_JoinOne("Телефон",array('Db_Lang_ServicesData'),'phone',null,false,'200'),
			'fax' => new ArOn_Crud_Grid_Column_JoinOne("Факс",array('Db_Lang_ServicesData'),'fax',null,false,'200'),
			'email' => new ArOn_Crud_Grid_Column_JoinOne("E-mail",array('Db_Lang_ServicesData'),'email',null,false,'200'),
			'web_address' => new ArOn_Crud_Grid_Column_JoinOne("Веб сайт",array('Db_Lang_ServicesData'),'web_address',null,false,'200'),

			//'brands_languages_id' => new ArOn_Crud_Grid_Column_JoinOne("Язык бренда",array('Db_Lang_BrandsData'),'languages_id',null,false,'200'),
			'name' => new ArOn_Crud_Grid_Column_JoinOne("Название компании",array('Db_Lang_ServicesData'),'name',null,false,'200'),
			'regions_id' => new ArOn_Crud_Grid_Column_JoinOne("Регион",array('Db_LocationCities','Db_LocationCountries'),'regions_id',null,false,'200'),
			'countries_id' => new ArOn_Crud_Grid_Column_JoinOne("Страна",array('Db_LocationCities'),'countries_id',null,false,'200'),
			'cities_id' => new ArOn_Crud_Grid_Column_Numeric("Город",null,true,false,'50'),
			'category_id' => new ArOn_Crud_Grid_Column_Numeric("Категория",'service_companies_categories_id',true,false,'50'),
			//'active' => new ArOn_Crud_Grid_Column_Numeric('Активный',null,true,false,'50'),
			'logo' => new ArOn_Crud_Grid_Column_JoinOne("Описание компании",array('Db_Lang_ServicesData'),'logo',null,false,'200'),
			//'image' => new ArOn_Crud_Grid_Column_JoinOne("Описание компании",array('Db_Lang_ServicesData'),'image_logo',null,false,'200'),
		);
		//$this->fields['active']->options = array('1'=>"+", '0'=>"-");

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'id' => new ArOn_Crud_Grid_Filter_Field_Value('id','ID',ArOn_Db_Filter_Field::EQ),
			'languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('languages_id','Язык выставки',array(
				array(
					'path' => array('Db_Lang_ServicesData'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'active' => new ArOn_Crud_Grid_Filter_Field_Value('active','Активный',ArOn_Db_Filter_Field::EQ),
		);
		$this->_params['languages_id'] = DEFAULT_LANG_ID;
		$this->_params['active'] = 1;

		parent::init();
	}
}
