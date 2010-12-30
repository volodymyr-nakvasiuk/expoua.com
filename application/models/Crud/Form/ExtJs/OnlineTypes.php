<?php
class Crud_Form_ExtJs_OnlineTypes extends ArOn_Crud_Form_ExtJs
{
	protected $modelName = 'Db_OnlineTypes';
	protected $_title = 'Онлайн выставка - тип стенда';

	public function init ()
	{
		$this->action = '/' . self::$ajaxModuleName . '/online-types/save/';
		$this->actionName = 'online-types';

		$this->fields = array(
			'id' => new ArOn_Crud_Form_Field_Numeric('id', 'Id') ,
			'name' => new ArOn_Crud_Form_Field_Text('name',"Название"),
			'width' => new ArOn_Crud_Form_Field_Numeric('width', 'Ширина'),
			'height' => new ArOn_Crud_Form_Field_Numeric('height', 'Высота'),
			'size' => new ArOn_Crud_Form_Field_Array2Select('size', 'Размер логотипа', null, true),
			'banner' => new ArOn_Crud_Form_Field_Array2Select('banner', 'Баннер', null, true),
		);

		$this->fields['size']->setOptions(array('1' => "Большой (248x248)", '2' => "Средний (123x123)", '3' => "Маленький (53x53)"));
		$this->fields['banner']->setOptions(array('0' => "Нет", '1' => "Да"));

		if(empty($this->actionId)) unset($this->fields['id']);
		else $this->fields['id']->setElementHelper('formNotEdit');

		$this->fields['width']->setValue(968);
		
		$this->groups = array('0' => array_keys($this->fields));
		parent::init();
	}
}
