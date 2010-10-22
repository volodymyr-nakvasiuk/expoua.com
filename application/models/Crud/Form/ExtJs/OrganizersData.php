<?php
class Crud_Form_ExtJs_OrganizersData extends Crud_Form_ExtJs_Tabitem {

	protected $modelName = 'Db_Lang_OrganizersData';
	protected $_title = '';

	public function init () {
		$this->action = '/' . self::$ajaxModuleName . '/organizers-data/save/';
		$this->actionName = 'organizers-data';

		$this->fields = array(
			'id' => new ArOn_Crud_Form_Field_Numeric('id', 'Id', null, true, true) ,
			'db_languages_id' => new ArOn_Crud_Form_Field_Numeric('languages_id', 'Id языка', null, true, true) ,
			'name' => new ArOn_Crud_Form_Field_Text('name', 'Название', null, true) ,
			'description' => new ArOn_Crud_Form_Field_TextArea('description', 'Описание') ,
			'phone' => new ArOn_Crud_Form_Field_Text('phone', 'Телефон', null, true),
			'fax' => new ArOn_Crud_Form_Field_Text('fax', 'Факс', null, true),
			'email' => new ArOn_Crud_Form_Field_Text('email', 'Почта', null, true),
			'web_address' => new ArOn_Crud_Form_Field_Text('web_address', 'Сайт', null, true),
			'address' => new ArOn_Crud_Form_Field_Text('address', 'Адрес', null, true),
			'postcode' => new ArOn_Crud_Form_Field_Text('postcode', 'Индекс', null, false),
			'cont_pers_name' => new ArOn_Crud_Form_Field_Text('cont_pers_name', 'Контактное лицо', null, false),
			'cont_pers_phone' => new ArOn_Crud_Form_Field_Text('cont_pers_phone', 'Телефон КЛ', null, false),
			'cont_pers_email' => new ArOn_Crud_Form_Field_Text('cont_pers_email', 'Почта КЛ', null, false),
		);
		$this->fields['db_languages_id']->hidden = true;
		if(empty($this->actionId)) unset($this->fields['id']);
		else $this->fields['id']->setElementHelper('formNotEdit');

		$this->groups = array('0' => array_keys($this->fields));
		parent::init();
	}
}
