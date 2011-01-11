<?php
class Crud_Form_ExtJs_OnlineShowrooms extends ArOn_Crud_Form_ExtJs
{
	protected $modelName = 'Db_OnlineShowRooms';
	protected $_title = 'Онлайн выставка - выставочный зал';

	public function init ()
	{
		$this->action = '/' . self::$ajaxModuleName . '/online-showrooms/save/';
		$this->actionName = 'online-showrooms';

		$this->fields = array(
			'id' => new ArOn_Crud_Form_Field_Numeric('id', 'Id') ,
			'category' => new ArOn_Crud_Form_Field_Many2One('brands_categories_id', 'Тип', null, false),
			'name' => new ArOn_Crud_Form_Field_Text('name',"Название"),
			'width' => new ArOn_Crud_Form_Field_Numeric('width', 'Ширина'),
			'height' => new ArOn_Crud_Form_Field_Numeric('height', 'Высота'),
			'order' => new ArOn_Crud_Form_Field_Numeric('order', 'Порядок сортировки'),
		);

		$this->fields['category']->model = 'Db_Lang_BrandsCategoriesData';

		if(empty($this->actionId)) unset($this->fields['id']);
		else $this->fields['id']->setElementHelper('formNotEdit');

		$this->fields['width']->setValue(968);
		
		$this->groups = array('0' => array_keys($this->fields));
		parent::init();
	}
}
