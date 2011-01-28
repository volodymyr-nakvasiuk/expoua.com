<?php
class Crud_Grid_Venue extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "id";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'venues-tab';
	protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Выставочный центр';
		//$this->where = 'active=1';

		$this->gridActionName = 'venues';
		$this->table = "Db_Expocenters";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			//'languages_id' => new ArOn_Crud_Grid_Column_JoinOne("Язык выставки",array('Db_Lang_ExpocentersData'),'languages_id',null,false,'200'),
			'description' => new ArOn_Crud_Grid_Column_JoinOne("Описание",array('Db_Lang_ExpocentersData'),'description',null,false,'200'),
			'name' => new ArOn_Crud_Grid_Column_JoinOne("Название",array('Db_Lang_ExpocentersData'),'name',null,false,'200'),
			'regions_id' => new ArOn_Crud_Grid_Column_JoinOne("Регион",array('Db_LocationCities','Db_LocationCountries'),'regions_id',null,false,'200'),
			'countries_id' => new ArOn_Crud_Grid_Column_JoinOne("Страна",array('Db_LocationCities'),'countries_id',null,false,'200'),
			'cities_id' => new ArOn_Crud_Grid_Column_Numeric("Город",null,true,false,'50'),
			//'active' => new ArOn_Crud_Grid_Column_Numeric('Активный',null,true,false,'50'),
		);
		//$this->fields['active']->options = array('1'=>"+", '0'=>"-");

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'search' => new ArOn_Crud_Grid_Filter_Field_Search('search','Язык бренда',array(
				array(
					'path' => array('Db_Lang_ExpocentersData'),
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
					'path' => array('Db_Lang_ExpocentersData'),
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
