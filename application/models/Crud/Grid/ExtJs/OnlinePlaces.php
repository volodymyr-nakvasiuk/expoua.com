<?php
class Crud_Grid_ExtJs_OnlinePlaces extends ArOn_Crud_Grid_ExtJs {

	protected $_idProperty = 'id';
	public $sort = "showrooms_order";
	public $direction = "ASC";
	//public $editController = 'grid';
	public $editAction = 'online-places';
	//protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Онлайн выставка - выставочные стенд';

		$this->gridActionName = 'online-places';
		$this->table = "Db_OnlinePlaces";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			'showrooms_order' => new ArOn_Crud_Grid_Column_Numeric("Номер в зале",null,true,false,'100'),
			//'showrooms_id' => new ArOn_Crud_Grid_Column_Numeric("Дата создания",null,true,false,'100'),
			'types_name' => new ArOn_Crud_Grid_Column_JoinOne("Тип стенда",array('Db_OnlineTypes'),'name',null,false,'100'),
			//'types_id' => new ArOn_Crud_Grid_Column_Numeric("Дата создания",null,true,false,'100'),
			'showrooms_name' => new ArOn_Crud_Grid_Column_JoinOne("Выставочный зал",array('Db_OnlineShowRooms'),'name',null,false,'100'),
			//'companies_id' => new ArOn_Crud_Grid_Column_Numeric("Компания",null,true,false,'100'),
			'companies_name' => new ArOn_Crud_Grid_Column_JoinOne("Название компании",array('Db_Companies','Db_Lang_CompaniesData'),'name',null,false,'200'),
			//'description' => new ArOn_Crud_Grid_Column_JoinOne("Описание компании",array('Db_Companies','Db_Lang_CompaniesData'),'description',null,false,'200'),
			//'cities_id' => new ArOn_Crud_Grid_Column_JoinOne("Город",array('Db_Companies'),'cities_id',null,false,'200'),
			//'logo' => new ArOn_Crud_Grid_Column_JoinOne("Город",array('Db_Companies'),'logo',null,false,'200'),
			'top' => new ArOn_Crud_Grid_Column_Numeric("По вертикали",null,true,false,'100'),
			'left' => new ArOn_Crud_Grid_Column_Numeric("По горизонтали",null,true,false,'100'),
			'width' => new ArOn_Crud_Grid_Column_JoinOne("Ширина",array('Db_OnlineTypes'),'width',null,false,'100'),
			'height' => new ArOn_Crud_Grid_Column_JoinOne("Высота",array('Db_OnlineTypes'),'height',null,false,'100'),
			'banner' => new ArOn_Crud_Grid_Column_JoinOne("Вставлять ли баннер",array('Db_OnlineTypes'),'banner',null,false,'200'),
		);

		$this->fields['banner']->options = array('1'=>"да", '0'=>"нет");

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
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
