<?php
class Crud_Form_ExtJs_BrandsSubcategoriesData extends Crud_Form_ExtJs_Tabitem {

	protected $modelName = 'Db_Lang_BrandsSubcategoriesData';
	protected $_title = '';

	public function init () {
		$this->action = '/' . self::$ajaxModuleName . '/brands-subcategories-data/save/';
		$this->actionName = 'brands-subcategories-data';

		$this->fields = array(
			'id' => new ArOn_Crud_Form_Field_Numeric('id', 'Id', null, true, true) ,
			'db_languages_id' => new ArOn_Crud_Form_Field_Numeric('languages_id', 'Id языка', null, true, true) ,
			'name' => new ArOn_Crud_Form_Field_Text('name', 'Название', null, true) ,
		);
		$this->fields['db_languages_id']->hidden = true;
		if(empty($this->actionId)) unset($this->fields['id']);
		else $this->fields['id']->setElementHelper('formNotEdit');

		$this->groups = array('0' => array_keys($this->fields));
		parent::init();
	}
}
