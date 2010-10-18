<?php
class Crud_Grid_ExtJs_LocationCities extends ArOn_Crud_Grid_ExtJs {

	protected $_idProperty = 'id';
	public $sort = "id";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'location-cities-tab';

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Города';

		$this->gridActionName = 'location-cities';
		$this->table = "Db_Lang_LocationCitiesData";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			'region' => new ArOn_Crud_Grid_Column_JoinOne("Регион",array('Db_LocationCities','Db_LocationCountries','Db_Lang_LocationRegionsData'),'name',null,false,'200'),
			'country' => new ArOn_Crud_Grid_Column_JoinOne("Страна",array('Db_LocationCities','Db_Lang_LocationCountriesData'),'name',null,false,'200'),
			'name' => new ArOn_Crud_Grid_Column_Default("Город",null,true,false,'200'),
			'active' => new ArOn_Crud_Grid_Column_JoinOne('Активный','Db_LocationCities', 'active', null, false, '50'),
		);
		$this->fields['active']->options = array('1'=>"+", '0'=>"-");

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'search' => new ArOn_Crud_Grid_Filter_Field_Search('search','Search:', array(
				array(
					'path' => null,
					'filters' => array(
						ArOn_Db_Filter_Search::ID => ArOn_Db_Filter_Search::EQ,
						ArOn_Db_Filter_Search::NAME => ArOn_Db_Filter_Search::LIKE
					),
				),
				array(
					'path' => array('Db_LocationCities','Db_Lang_LocationCountriesData'),
					'filters' => array(
						ArOn_Db_Filter_Search::NAME => ArOn_Db_Filter_Search::LIKE
					),
				),
				array(
					'path' => array('Db_LocationCities','Db_LocationCountries','Db_Lang_LocationRegionsData'),
					'filters' => array(
						ArOn_Db_Filter_Search::NAME => ArOn_Db_Filter_Search::LIKE
					),
				),
			)),
			'id' => new ArOn_Crud_Grid_Filter_Field_Value('id', 'id:',ArOn_Db_Filter_Field::EQ),
		);

		parent::init();
	}
}
