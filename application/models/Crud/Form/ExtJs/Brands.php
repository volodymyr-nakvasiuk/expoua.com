<?php
class Crud_Form_ExtJs_Brands extends Crud_Form_ExtJs_Tabitem {

	protected $modelName = 'Db_Brands';
	protected $_title = 'Все языки';

	public function init () {
		$this->action = '/' . self::$ajaxModuleName . '/brands/save/';
		$this->actionName = 'brands';

		$this->fields = array(
			'id' => new ArOn_Crud_Form_Field_Numeric('id', 'Id', null, true, true),
			'organizer' => new ArOn_Crud_Form_Field_Many2One('organizers_id','Организатор'),
			'main_category' => new ArOn_Crud_Form_Field_Many2One('brands_categories_id','Основная категория'),
			'category' => new ArOn_Crud_Form_Field_Many2Many('brands_categories','Категории'),
			'subcategory' => new ArOn_Crud_Form_Field_Many2Many('brands_subcategories','Подкатегории'),
			'email_requests' => new ArOn_Crud_Form_Field_Text('email_requests', 'Email для запросов', null, false),
			'dead' => new ArOn_Crud_Form_Field_Array2Select('dead', 'Статус', null, true)
		);
		$this->fields['organizer']->model = 'Db_Lang_OrganizersData';
		$this->fields['main_category']->model = 'Db_Lang_BrandsCategoriesData';

		$this->fields['dead']->setOptions(array('0' => "Активный", '1' => "Не активный"));
		if(empty($this->actionId)) unset($this->fields['id']);
		else $this->fields['id']->setElementHelper('formNotEdit');

		$this->fields['category']->helper = array(
			'model' => 'Db_Lang_BrandsCategoriesData',
			'workingModel' => 'Db_Brands2Categories'
		);
		$this->fields['category']->setElementHelper('formMultiSelect');
		$this->fields['category']->setExplode(',');

		$this->fields['subcategory']->addAttrib('actionId', $this->actionId);
		$this->fields['subcategory']->helper = array(
			'model' => 'Db_Lang_BrandsSubcategoriesData',
			'workingModel' => 'Db_Brands2Subcategories',
			'category' => array(array('Db_BrandsSubcategories','Db_Lang_BrandsCategoriesData'))
		);
		$this->fields['subcategory']->setElementHelper('formMultiSelect');
		$this->fields['subcategory']->setExplode(',');


		$this->groups = array('0' => array_keys($this->fields));
		parent::init();
	}
}
