<?php
class Crud_Grid_Companies extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "date_modify";
	public $direction = "DESC";
	public $editController = 'grid';
	public $editAction = 'companies-tab';
	//protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Компании';
		//$this->where = 'active=1';

		$this->gridActionName = 'companies';
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
			'logo' => new ArOn_Crud_Grid_Column_Numeric("Город",null,true,false,'50'),

			'active' => new ArOn_Crud_Grid_Column_JoinOne("Активный",array('Db_CompaniesActive'),'active',null,false,'200'),
			'news_count'=>new ArOn_Crud_Grid_Column_JoinOne("Количество новостей",array('Db_Lang_CompaniesData'),'news_count',null,false,'200'),
			'services_count'=>new ArOn_Crud_Grid_Column_JoinOne("Количество продуктов и сервисов",array('Db_Lang_CompaniesData'),'services_count',null,false,'200'),
		);
		//$this->fields['active']->options = array('1'=>"+", '0'=>"-");

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'search' => new ArOn_Crud_Grid_Filter_Field_Search('search','Язык бренда',array(
				array(
					'path' => array('Db_Lang_CompaniesData'),
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
			'logo' => new ArOn_Crud_Grid_Filter_Field_Value('logo','Город',ArOn_Db_Filter_Field::EQ),
			'languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('languages_id','Язык выставки',array(
				array(
					'path' => array('Db_Lang_CompaniesData'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'category' => new ArOn_Crud_Grid_Filter_Field_Select2('id','Категория', 'Db_BrandsCategories', array('Db_Companies2Categories', 'Db_BrandsCategories')),
			'active_languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('active_languages_id','Язык выставки',array(
				array(
					'path' => array('Db_CompaniesActive'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'active' => new ArOn_Crud_Grid_Filter_Field_Search('active','Язык выставки',array(
				array(
					'path' => array('Db_CompaniesActive'),
					'filters' => array(
						'active' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
		);
		$this->_params['languages_id'] = DEFAULT_LANG_ID;
		$this->_params['active_languages_id'] = DEFAULT_LANG_ID;
		$this->_params['active'] = 1;

		parent::init();
	}
}
