<?php
class Crud_Grid_ExtJs_Languages extends ArOn_Crud_Grid_ExtJs {

	protected $_idProperty = 'id';
	public $sort = "id";
	public $direction = "ASC";

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Языки';

		$this->gridActionName = 'languages';
		$this->table = "Db_Languages";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			'name' => new ArOn_Crud_Grid_Column_Default("Язык",null,false,false,'200'),
			'locale' => new ArOn_Crud_Grid_Column_Default("Локаль",null,false,false,'100'),
			'code' => new ArOn_Crud_Grid_Column_Default("Код",null,false,false,'50'),
			'active' => new ArOn_Crud_Grid_Column_Array("Активный",null,true,false,'200')
		);
		$this->fields['active']->options = array('1' => "+", '0' => "Не активный");

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
