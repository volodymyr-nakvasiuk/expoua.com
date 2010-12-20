<?php
class Crud_Grid_EventFiles extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "id";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'event-files-tab';
	protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Файлы выставки';

		$this->gridActionName = 'files';
		$this->table = "Db_EventFiles";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			'name' => new ArOn_Crud_Grid_Column_Default("Название документа",null,true,false,'100'),
			'content_type' => new ArOn_Crud_Grid_Column_Default("Тип файла",null,true,false,'100'),
			'size' => new ArOn_Crud_Grid_Column_Default("Размер файла (байтов)",null,true,false,'100'),
		);

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'events_id' => new ArOn_Crud_Grid_Filter_Field_Value('events_id','ID',ArOn_Db_Filter_Field::EQ),
		);

		parent::init();
	}
}
