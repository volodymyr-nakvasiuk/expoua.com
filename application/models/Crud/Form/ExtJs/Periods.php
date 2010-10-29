<?php
class Crud_Form_ExtJs_Periods extends Crud_Form_ExtJs_Tabitem {

	protected $modelName = 'Db_Periods';
	protected $_title = 'Все языки';

	public function init () {
		$this->action = '/' . self::$ajaxModuleName . '/periods/save/';
		$this->actionName = 'periods';

		$this->fields = array(
			'id' => new ArOn_Crud_Form_Field_Numeric('id', 'Id', null, true, true) ,
			'active' => new ArOn_Crud_Form_Field_Array2Select('active', 'Статус', null, true),
			'monthes' => new ArOn_Crud_Form_Field_Numeric('monthes', 'К-во месяцев', null, true),
		);
		$this->fields['active']->setOptions(array('1' => "Активный", '0' => "Не активный"));
		if(empty($this->actionId)) unset($this->fields['id']);
		else $this->fields['id']->setElementHelper('formNotEdit');

		$this->groups = array('0' => array_keys($this->fields));
		parent::init();
	}
}
