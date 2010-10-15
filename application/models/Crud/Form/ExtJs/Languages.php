<?php
class Crud_Form_ExtJs_Languages extends ArOn_Crud_Form_ExtJs {

	protected $modelName = 'Db_Languages';
	protected $_title = 'Язык';

	public function init () {
		$this->action = '/' . self::$ajaxModuleName . '/languages/save/';
		$this->actionName = 'languages';

		$this->fields = array(
			'id' => new ArOn_Crud_Form_Field_Numeric('id', 'Id', null, true, true) ,
			'name' => new ArOn_Crud_Form_Field_Text('name', 'Название', null, true) ,
			'locale' => new ArOn_Crud_Form_Field_Text('locale', 'Локаль', null, true) ,
			'code' => new ArOn_Crud_Form_Field_Text('code', 'Код языка', null, true) ,
			'active' => new ArOn_Crud_Form_Field_Array2Select('active', 'Статус', null, true)
		);
		$this->fields['active']->setOptions(array('1' => "Активный", '0' => "Не активный"));
		if(empty($this->actionId)) unset($this->fields['id']);
		else $this->fields['id']->setElementHelper('formNotEdit');

		$this->groups = array('0' => array_keys($this->fields));
		parent::init();
	}
}
