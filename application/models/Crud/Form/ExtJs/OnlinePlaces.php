<?php
class Crud_Form_ExtJs_OnlinePlaces extends ArOn_Crud_Form_ExtJs
{
	protected $modelName = 'Db_OnlinePlaces';
	protected $_title = 'Онлайн выставка - настрока стенда';

	public function init ()
	{
		$this->action = '/' . self::$ajaxModuleName . '/online-places/save/';
		$this->actionName = 'online-places';

		$this->fields = array(
			'id' => new ArOn_Crud_Form_Field_Numeric('id', 'Id'),
			'logo' => new ArOn_Crud_Form_Field_AdminImageUpload('logo',UPLOAD_IMAGES_PATH.'/online', '{company}', 2, 'Логотип', null, '5242880', false, false, 150, false, array('big'=>'248x248','middle'=>'123x123','small'=>'53x53')),
			'company' => new ArOn_Crud_Form_Field_Numeric('companies_id', 'ID Компании'),
			'showroom' => new ArOn_Crud_Form_Field_Many2One('showrooms_id', 'Выставочный зал', null, false),
			'type' => new ArOn_Crud_Form_Field_Many2One('types_id', 'Тип', null, false),
			'left' => new ArOn_Crud_Form_Field_Numeric('left', 'Отступ по горизонтали') ,
			'top' => new ArOn_Crud_Form_Field_Numeric('top', 'Отступ по вертикали') ,
			'showrooms_order' => new ArOn_Crud_Form_Field_Numeric('showrooms_order', 'Номер в зале') ,
		);

		//$this->fields['company']->model = 'Db_Lang_CompaniesData';
		$this->fields['showroom']->model = 'Db_OnlineShowRooms';
		$this->fields['type']->model = 'Db_OnlineTypes';

		if(empty($this->actionId)) unset($this->fields['id']);
		else $this->fields['id']->setElementHelper('formNotEdit');

		$this->groups = array('0' => array_keys($this->fields));
		parent::init();
	}

	public function saveValidData(){
		$presave_data = $this->getData();
		if (!$presave_data['company']){
			$this->fields['company']->noSave();
		}
		parent::saveValidData();
	}
}
