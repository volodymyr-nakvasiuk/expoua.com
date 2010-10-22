<?php
class Crud_Form_ExtJs_SocialOrganizationsData extends Crud_Form_ExtJs_Tabitem {

	protected $modelName = 'Db_Lang_SocialOrganizationsData';
	protected $_title = '';

	public function init () {
		$this->action = '/' . self::$ajaxModuleName . '/social-organizations-data/save/';
		$this->actionName = 'social-organizations-data';

		$this->fields = array(
			'id' => new ArOn_Crud_Form_Field_Numeric('id', 'Id', null, true, true) ,
			'db_languages_id' => new ArOn_Crud_Form_Field_Numeric('languages_id', 'Id языка', null, true, true) ,
			'name' => new ArOn_Crud_Form_Field_Text('name', 'Название', null, true) ,
			'description' => new ArOn_Crud_Form_Field_TextArea('description', 'Описание') ,
			'phone' => new ArOn_Crud_Form_Field_Text('phone', 'Телефон', null, false),
			'fax' => new ArOn_Crud_Form_Field_Text('fax', 'Факс', null, false),
			'email' => new ArOn_Crud_Form_Field_Text('email', 'Почта', null, false),
			'web_address' => new ArOn_Crud_Form_Field_Text('web_address', 'Сайт', null, false),
			'address' => new ArOn_Crud_Form_Field_Text('address', 'Адрес', null, true),
			'postcode' => new ArOn_Crud_Form_Field_Text('postcode', 'Индекс', null, false),

		);
		$this->fields['db_languages_id']->hidden = true;
		if(empty($this->actionId)) unset($this->fields['id']);
		else $this->fields['id']->setElementHelper('formNotEdit');

		$this->groups = array('0' => array_keys($this->fields));
		parent::init();
	}
}
