<?php
class Crud_Grid_EventNews extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "date_public";
	public $direction = "DESC";
	public $editController = 'grid';
	public $editAction = 'event-news-tab';
	//protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Новости выставки';

		$this->gridActionName = 'news';
		$this->table = "Db_EventNews";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			'events_id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			'date_public' => new ArOn_Crud_Grid_Column_Default("Дата",null,true,false,'100'),
			'date_created' => new ArOn_Crud_Grid_Column_Default("Дата создания",null,true,false,'100'),
			//'languages_id' => new ArOn_Crud_Grid_Column_JoinOne("Язык новости",array('Db_Lang_EventNewsData'),'languages_id',null,false,'200'),
			'name' => new ArOn_Crud_Grid_Column_JoinOne("Название новости",array('Db_Lang_EventNewsData'),'name',null,false,'200'),
			'preambula' => new ArOn_Crud_Grid_Column_JoinOne("Описание новости",array('Db_Lang_EventNewsData'),'preambula',null,false,'200'),
			//'content' => new ArOn_Crud_Grid_Column_JoinOne("Текст новости",array('Db_Lang_EventNewsData'),'content',null,false,'200'),
			//'active' => new ArOn_Crud_Grid_Column_JoinOne("Активна ли новость на этом языке",array('Db_Lang_EventNewsData'),'active',null,false,'200'),
			'events_id' => new ArOn_Crud_Grid_Column_Numeric("Дата создания",null,true,false,'100'),

			'regions_id' => new ArOn_Crud_Grid_Column_JoinOne("Регион",array('Db_Events', 'Db_LocationCities','Db_LocationCountries'),'regions_id',null,false,'200'),
			'countries_id' => new ArOn_Crud_Grid_Column_JoinOne("Страна",array('Db_Events', 'Db_LocationCities'),'countries_id',null,false,'200'),
			'cities_id' => new ArOn_Crud_Grid_Column_JoinOne("Город",array('Db_Events'),'cities_id',null,false,'200'),
			'category' => new ArOn_Crud_Grid_Column_JoinOne('Категория', array('Db_Events', 'Db_Brands', 'Db_Brands2Categories', 'Db_BrandsCategories'), null, array('id')),
			'subcategory' => new ArOn_Crud_Grid_Column_JoinOne('Подкатегория', array('Db_Events', 'Db_Brands', 'Db_Brands2Subcategories', 'Db_BrandsSubcategories'), null, array('id')),
		);

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('languages_id','Язык новости',array(
				array(
					'path' => array('Db_Lang_EventNewsData'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'active' => new ArOn_Crud_Grid_Filter_Field_Search('active','Активна ли новость на этом языке',array(
				array(
					'path' => array('Db_Lang_EventNewsData'),
					'filters' => array(
						'active' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'brands_id' => new ArOn_Crud_Grid_Filter_Field_Value('brands_id','ID',ArOn_Db_Filter_Field::EQ),
			'events_id' => new ArOn_Crud_Grid_Filter_Field_Value('events_id','ID',ArOn_Db_Filter_Field::EQ),

			'search' => new ArOn_Crud_Grid_Filter_Field_Search('search','Язык бренда',array(
				array(
					'path' => array('Db_Lang_EventNewsData'),
					'filters' => array(
						'name' => ArOn_Db_Filter_Search::LIKE,
					),
				),
			)),
			'region' => new ArOn_Crud_Grid_Filter_Field_Search('regions_id','Регион', array(
				array(
					'path' => array('Db_Events', 'Db_LocationCities','Db_LocationCountries'),
					'filters' => array(
						'regions_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'country' => new ArOn_Crud_Grid_Filter_Field_Search('countries_id','Страна',array(
				array(
					'path' => array('Db_Events', 'Db_LocationCities'),
					'filters' => array(
						'countries_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'city' => new ArOn_Crud_Grid_Filter_Field_Search('cities_id','Город',array(
				array(
					'path' => array('Db_Events'),
					'filters' => array(
						'cities_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'category' => new ArOn_Crud_Grid_Filter_Field_Select2('id','Категория', 'Db_BrandsCategories', array('Db_Events', 'Db_Brands', 'Db_Brands2Categories', 'Db_BrandsCategories')),
			'subcategory' => new ArOn_Crud_Grid_Filter_Field_Select2('id','Подкатегория', 'Db_BrandsSubcategories', array('Db_Events', 'Db_Brands', 'Db_Brands2Subcategories', 'Db_BrandsSubcategories')),
		);
		$this->_params['active'] = 1;
		$this->_params['languages_id'] = DEFAULT_LANG_ID;

		parent::init();
	}
}
