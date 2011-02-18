<?php
class Crud_Grid_ServicesCategories extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "Db_Lang_ServicesCategoriesData.name";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'services-categories-tab';
	protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Категории компаний';
		//$this->where = 'active=1';

		$this->gridActionName = 'services-categories';
		$this->table = "Db_Services";
		$this->fields = array(
			'name' => new ArOn_Crud_Grid_Column_JoinOne("Описание выставки",array('Db_Lang_ServicesCategoriesData'),'name',null,false,'200'),
		);

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'services_categories_languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('services_categories_languages_id','Язык бренда',array(
				array(
					'path' => array('Db_Lang_ServicesCategoriesData'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'active' => new ArOn_Crud_Grid_Filter_Field_Value('active','Активный',ArOn_Db_Filter_Field::EQ),
		);
		$this->_params['services_categories_languages_id'] = DEFAULT_LANG_ID;
		$this->_params['active'] = 1;

		parent::init();
	}

	protected function updateCurrentSelect($select) {
		$select->group('service_companies_categories_id')->columns(
			array(
				"c" => new Zend_Db_Expr("COUNT(*)")
			)
		);

		return parent::updateCurrentSelect($select);
	}
}
