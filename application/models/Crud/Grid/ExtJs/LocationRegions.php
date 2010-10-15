<?php
class Crud_Grid_ExtJs_LocationRegions extends ArOn_Crud_Grid_ExtJs {

	protected $_idProperty = 'id';
	public $sort = "id";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'location-regions-tab';

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Регионы';

		$this->gridActionName = 'location-regions';
		$this->table = "Db_Lang_LocationRegionsData";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'20'),
			'name' => new ArOn_Crud_Grid_Column_Default("Регион",null,true,false,'50'),
			'active' => new ArOn_Crud_Grid_Column_JoinOne('Активный','Db_LocationRegions', 'active', null, false, '50'),
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
			)),
			'id' => new ArOn_Crud_Grid_Filter_Field_Value('id', 'id:',ArOn_Db_Filter_Field::EQ),
		);

		parent::init();
	}
}
