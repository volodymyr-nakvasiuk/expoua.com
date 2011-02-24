<?php
class Crud_Grid_ServiceGallery extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "id";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'event-gallery-tab';
	protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Галерея сервисной компании';

		$this->gridActionName = 'gallery';
		$this->table = "Db_ServicesGallery";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
		);

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'company_id' => new ArOn_Crud_Grid_Filter_Field_Value('servcomps_id','ID',ArOn_Db_Filter_Field::EQ),
		);

		parent::init();
	}
}
