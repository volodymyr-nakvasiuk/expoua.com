<?php
class Crud_Form_ExtJs_BrandsSubcategories extends Crud_Form_ExtJs_Tabitem {

	protected $modelName = 'Db_BrandsSubcategories';
	protected $_title = 'Все языки';

	public function init () {
		$this->action = '/' . self::$ajaxModuleName . '/brands-subcategories/save/';
		$this->actionName = 'brands-subcategories';

		$this->fields = array(
			'id' => new ArOn_Crud_Form_Field_Numeric('id', 'Id', null, true, true) ,
			'category' => new ArOn_Crud_Form_Field_Many2One('parent_id','Регион'),
			'active' => new ArOn_Crud_Form_Field_Array2Select('active', 'Статус', null, true)
		);
		$this->fields['category']->model = 'Db_Lang_BrandsCategoriesData';
		$this->fields['active']->setOptions(array('1' => "Активный", '0' => "Не активный"));
		if(empty($this->actionId)) unset($this->fields['id']);
		else $this->fields['id']->setElementHelper('formNotEdit');

		$this->groups = array('0' => array_keys($this->fields));
		parent::init();
	}
}
