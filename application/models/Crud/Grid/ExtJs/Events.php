<?php
class Crud_Grid_ExtJs_Events extends ArOn_Crud_Grid_ExtJs {

	protected $_idProperty = 'id';
	public $sort = "id";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'events-tab';
	protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'События';

		$this->gridActionName = 'events';
		$this->table = "Db_Lang_EventsData";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			//'city' => new ArOn_Crud_Grid_Column_JoinOne("Город",array('Db_Events','Db_Lang_LocationCitiesData'),'name',null,false,'200'),
			//'name' => new ArOn_Crud_Grid_Column_JoinOne("Название",array('Db_Events','Db_Lang_BrandsData'),'name',null,false,'400'),
			//'expocenter' => new ArOn_Crud_Grid_Column_JoinOne("Експоцентр",array('Db_Events','Db_Lang_ExpocentersData'),'name',null,false,'200'),
			//'phone' => new ArOn_Crud_Grid_Column_Default("Телефон",null,true,false,'100'),
			'active' => new ArOn_Crud_Grid_Column_JoinOne('Активный','Db_Events', 'active', null, false, '50'),
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
				/*
				array(
					'path' => array('Db_Events','Db_Lang_LocationCitiesData'),
					'filters' => array(
						ArOn_Db_Filter_Search::NAME => ArOn_Db_Filter_Search::LIKE
					),
				),
				 */
			)),
			'id' => new ArOn_Crud_Grid_Filter_Field_Value('id', 'id:',ArOn_Db_Filter_Field::EQ),
		);

		parent::init();
	}
}
