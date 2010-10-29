<?php
class Crud_Grid_ExtJs_Brands extends ArOn_Crud_Grid_ExtJs {

	protected $_idProperty = 'id';
	public $sort = "id";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'brands-tab';

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Бренды';

		$this->gridActionName = 'brands';
		$this->table = "Db_Lang_BrandsData";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			'organizer' => new ArOn_Crud_Grid_Column_JoinOne('Организатор',array('Db_Brands','Db_Lang_OrganizersData'),'name',null,false,'200'),
			'main_category' => new ArOn_Crud_Grid_Column_JoinOne("Основная категория",array('Db_Brands','Db_Lang_BrandsCategoriesData'),'name',null,false,'200'),
			'name' => new ArOn_Crud_Grid_Column_Default("Название",null,true,false,'200'),
			'dead' => new ArOn_Crud_Grid_Column_JoinOne('Активный','Db_Brands', 'dead', null, false, '50'),
		);
		$this->fields['dead']->options = array('1'=>"-", '0'=>"+");

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
					'path' => array('Db_Brands','Db_Lang_BrandsCategoriesData'),
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
