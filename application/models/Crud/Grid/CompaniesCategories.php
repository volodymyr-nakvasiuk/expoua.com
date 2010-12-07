<?php
class Crud_Grid_CompaniesCategories extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "name";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'companies-categories-tab';
	protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Категории компаний';
		//$this->where = 'active=1';

		$this->gridActionName = 'events';
		$this->table = "Db_Companies2Categories";
		$this->fields = array(
			'brands_categories_id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			'name' => new ArOn_Crud_Grid_Column_JoinOne("Описание выставки",array('Db_Lang_BrandsCategoriesData'),'name',null,false,'200'),
		);

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'brands_languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('brands_languages_id','Язык бренда',array(
				array(
					'path' => array('Db_Lang_BrandsCategoriesData'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
		);
		$this->_params['brands_languages_id'] = DEFAULT_LANG_ID;

		parent::init();
	}

	protected function updateCurrentSelect($select) {
		$select->group('id')->columns(
			array(
				"c" => new Zend_Db_Expr("COUNT(*)")
			)
		);
		
		return $select;
	}
}
