<?php
class Crud_Grid_Videos extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "date_added";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'videos-tab';
	//protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Видео с выставки';

		$this->gridActionName = 'videos';
		$this->table = "Db_Video_Videos";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			'title' => new ArOn_Crud_Grid_Column_Default("Название",null,true,false,'100'),
			'date_added' => new ArOn_Crud_Grid_Column_Numeric('Загружено (дата)',null,true,false,'50'),
			'description' => new ArOn_Crud_Grid_Column_Default("Описание",null,true,false,'100'),
			'video_file_id' => new ArOn_Crud_Grid_Column_Default("Имня файла",null,true,false,'100'),
			'duration' => new ArOn_Crud_Grid_Column_Numeric('Продолжительность',null,true,false,'50'),
		);

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'id_expo_event' => new ArOn_Crud_Grid_Filter_Field_Value('id_expo_event','Id выставки',ArOn_Db_Filter_Field::EQ),
			'publicity' => new ArOn_Crud_Grid_Filter_Field_Value('publicity','Опубликован',ArOn_Db_Filter_Field::EQ),
			'approve_status' => new ArOn_Crud_Grid_Filter_Field_Value('approve_status','Подтвержден',ArOn_Db_Filter_Field::EQ),
			'conv_status' => new ArOn_Crud_Grid_Filter_Field_Value('conv_status','Статус конвертации',ArOn_Db_Filter_Field::EQ),
		);
		$this->_params['publicity'] = 1;
		$this->_params['approve_status'] = 1;
		$this->_params['conv_status'] = 'Converted';

		parent::init();
	}
}
