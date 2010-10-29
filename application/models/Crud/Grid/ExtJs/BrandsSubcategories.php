<?php
class Crud_Grid_ExtJs_BrandsSubcategories extends ArOn_Crud_Grid_ExtJs {

	protected $_idProperty = 'id';
	public $sort = "id";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'brands-subcategories-tab';

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Подкатегории брендов';

		$this->gridActionName = 'brands-subcategories';
		$this->table = "Db_Lang_BrandsSubcategoriesData";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'20'),
			'category' => new ArOn_Crud_Grid_Column_JoinOne("Категория",array('Db_BrandsSubcategories','Db_Lang_BrandsCategoriesData'),'name',null,false,'400'),
			'name' => new ArOn_Crud_Grid_Column_Default("Подкатегория",null,true,false,'400'),
			'active' => new ArOn_Crud_Grid_Column_JoinOne('Активный','Db_BrandsSubcategories', 'active', null, false, '50'),
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
					'path' => array('Db_BrandsSubcategories','Db_Lang_BrandsCategoriesData'),
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
