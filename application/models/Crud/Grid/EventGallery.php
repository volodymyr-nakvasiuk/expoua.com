<?php
class Crud_Grid_EventGallery extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "id";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'event-gallery-tab';
	protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Галерея выставки';

		$this->gridActionName = 'gallery';
		$this->table = "Db_EventGallery";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
		);

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'events_id' => new ArOn_Crud_Grid_Filter_Field_Value('events_id','ID',ArOn_Db_Filter_Field::EQ),
		);

		parent::init();
	}
}
