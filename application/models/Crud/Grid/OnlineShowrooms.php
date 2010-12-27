<?php
class Crud_Grid_OnlineShowrooms extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "order";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'online-showrooms-tab';
	protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Онлайн выставка';

		$this->gridActionName = 'company';
		$this->table = "Db_OnlineShowrooms";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			'name' => new ArOn_Crud_Grid_Column_Default("Название",null,true,false,'100'),
			'width' => new ArOn_Crud_Grid_Column_Numeric("Ширина",null,true,false,'100'),
			'height' => new ArOn_Crud_Grid_Column_Numeric("Высота",null,true,false,'100'),
			'order' => new ArOn_Crud_Grid_Column_Numeric("Порядок сортировки",null,true,false,'100'),
		);

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'id' => new ArOn_Crud_Grid_Filter_Field_Value('id','ID',ArOn_Db_Filter_Field::EQ),
		);

		parent::init();
	}
}
