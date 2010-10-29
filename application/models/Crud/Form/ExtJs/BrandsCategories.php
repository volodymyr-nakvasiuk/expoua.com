<?php
class Crud_Form_ExtJs_BrandsCategories extends Crud_Form_ExtJs_Tabitem {

	protected $modelName = 'Db_BrandsCategories';
	protected $_title = 'Все языки';

	public function init () {
		$this->action = '/' . self::$ajaxModuleName . '/brands-categories/save/';
		$this->actionName = 'brands-categories';

		$this->fields = array(
			'id' => new ArOn_Crud_Form_Field_Numeric('id', 'Id', null, true, true),
			'active' => new ArOn_Crud_Form_Field_Array2Select('active', 'Статус', null, true),
		);
		$this->fields['active']->setOptions(array('1' => "Активный", '0' => "Не активный"));
		if(empty($this->actionId)) unset($this->fields['id']);
		else $this->fields['id']->setElementHelper('formNotEdit');

		$this->groups = array('0' => array_keys($this->fields));
		parent::init();
	}
}
