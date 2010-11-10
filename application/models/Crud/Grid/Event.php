<?php
class Crud_Grid_Event extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "date_from";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'events-tab';
	protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Событие';
		//$this->where = 'active=1';

		$this->gridActionName = 'events';
		$this->table = "Db_Events";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			'date_from' => new ArOn_Crud_Grid_Column_Default("Дата от",null,true,false,'100'),
			'date_to' => new ArOn_Crud_Grid_Column_Default("Дата до",null,true,false,'100'),
			//'languages_id' => new ArOn_Crud_Grid_Column_JoinOne("Язык выставки",array('Db_Lang_EventsData'),'languages_id',null,false,'200'),
			'description' => new ArOn_Crud_Grid_Column_JoinOne("Описание выставки",array('Db_Lang_EventsData'),'description',null,false,'200'),
			//'brands_languages_id' => new ArOn_Crud_Grid_Column_JoinOne("Язык бренда",array('Db_Lang_BrandsData'),'languages_id',null,false,'200'),
			'brands_name' => new ArOn_Crud_Grid_Column_JoinOne("Название выставки",array('Db_Lang_BrandsData'),'name',null,false,'200'),
			'brands_id' => new ArOn_Crud_Grid_Column_Numeric("Бренд",null,true,false,'50'),
			'regions_id' => new ArOn_Crud_Grid_Column_JoinOne("Регион",array('Db_LocationCities','Db_LocationCountries'),'regions_id',null,false,'200'),
			'countries_id' => new ArOn_Crud_Grid_Column_JoinOne("Страна",array('Db_LocationCities'),'countries_id',null,false,'200'),
			'cities_id' => new ArOn_Crud_Grid_Column_Numeric("Город",null,true,false,'50'),
			//'active' => new ArOn_Crud_Grid_Column_Numeric('Активный',null,true,false,'50'),
			'free_tickets' => new ArOn_Crud_Grid_Column_Numeric('Бесплатный пригласительный',null,true,false,'50'),
			'category' => new ArOn_Crud_Grid_Column_JoinOne('Категория', array('Db_Brands', 'Db_Brands2Categories', 'Db_BrandsCategories'), null, array('id')),
			'subcategory' => new ArOn_Crud_Grid_Column_JoinOne('Подкатегория', array('Db_Brands', 'Db_Brands2Subcategories', 'Db_BrandsSubcategories'), null, array('id')),
		);
		//$this->fields['active']->options = array('1'=>"+", '0'=>"-");

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'id' => new ArOn_Crud_Grid_Filter_Field_Value('id','ID',ArOn_Db_Filter_Field::EQ),
			'brands_languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('brands_languages_id','Язык бренда',array(
				array(
					'path' => array('Db_Lang_BrandsData'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('languages_id','Язык выставки',array(
				array(
					'path' => array('Db_Lang_EventsData'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
		);
		$this->_params['brands_languages_id'] = DEFAULT_LANG_ID;
		$this->_params['languages_id'] = DEFAULT_LANG_ID;

		parent::init();
	}
}
