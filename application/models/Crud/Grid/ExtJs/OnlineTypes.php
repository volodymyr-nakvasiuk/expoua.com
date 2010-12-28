<?php
class Crud_Grid_ExtJs_OnlineTypes extends ArOn_Crud_Grid_ExtJs {

	protected $_idProperty = 'id';
	public $sort = "id";
	public $direction = "ASC";
	//public $editController = 'grid';
	public $editAction = 'online-types';
	//protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Онлайн выставка - типы стендов';

		$this->gridActionName = 'online-types';
		$this->table = "Db_OnlineTypes";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			'name' => new ArOn_Crud_Grid_Column_Default("Название",null,true,false,'100'),
			'width' => new ArOn_Crud_Grid_Column_Numeric("Ширина",null,true,false,'100'),
			'height' => new ArOn_Crud_Grid_Column_Numeric("Высота",null,true,false,'100'),
			'banner' => new ArOn_Crud_Grid_Column_Numeric("Баннер (0-нет, 1-да)",null,true,false,'100'),
		);

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'id' => new ArOn_Crud_Grid_Filter_Field_Value('id','ID',ArOn_Db_Filter_Field::EQ),
			'search' => new ArOn_Crud_Grid_Filter_Field_Search('search','Поиск',
				array(
					ArOn_Db_Filter_Search::ID => ArOn_Db_Filter_Search::EQ,
					ArOn_Db_Filter_Search::NAME => ArOn_Db_Filter_Search::LIKE,
				)
			),
		);

		parent::init();
	}
}
