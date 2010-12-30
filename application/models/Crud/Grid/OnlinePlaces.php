<?php
class Crud_Grid_OnlinePlaces extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "showrooms_order";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'online-places-tab';
	protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Онлайн выставка';

		$this->gridActionName = 'company';
		$this->table = "Db_OnlinePlaces";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			//'logo' => new ArOn_Crud_Grid_Column_Numeric('Логотип',null,true,false,'50'),
			'showrooms_id' => new ArOn_Crud_Grid_Column_Numeric("Дата создания",null,true,false,'100'),
			'companies_id' => new ArOn_Crud_Grid_Column_Numeric("Компания",null,true,false,'100'),
			'showrooms_order' => new ArOn_Crud_Grid_Column_Numeric("Номер в зале",null,true,false,'100'),
			'name' => new ArOn_Crud_Grid_Column_JoinOne("Название компании",array('Db_Companies','Db_Lang_CompaniesData'),'name',null,false,'200'),
			'description' => new ArOn_Crud_Grid_Column_JoinOne("Описание компании",array('Db_Companies','Db_Lang_CompaniesData'),'description',null,false,'200'),
			'regions_id' => new ArOn_Crud_Grid_Column_JoinOne("Регион",array('Db_Companies', 'Db_LocationCities','Db_LocationCountries'),'regions_id',null,false,'200'),
			'countries_id' => new ArOn_Crud_Grid_Column_JoinOne("Страна",array('Db_Companies', 'Db_LocationCities'),'countries_id',null,false,'200'),
			'cities_id' => new ArOn_Crud_Grid_Column_JoinOne("Город",array('Db_Companies'),'cities_id',null,false,'200'),
			'logo' => new ArOn_Crud_Grid_Column_JoinOne("Город",array('Db_Companies'),'logo',null,false,'200'),
			'top' => new ArOn_Crud_Grid_Column_Numeric("По вертикали",null,true,false,'100'),
			'left' => new ArOn_Crud_Grid_Column_Numeric("По горизонтали",null,true,false,'100'),
			'width' => new ArOn_Crud_Grid_Column_JoinOne("Ширина",array('Db_OnlineTypes'),'width',null,false,'200'),
			'height' => new ArOn_Crud_Grid_Column_JoinOne("Высота",array('Db_OnlineTypes'),'height',null,false,'200'),
			'size' => new ArOn_Crud_Grid_Column_JoinOne("Тип размера лого",array('Db_OnlineTypes'),'size',null,false,'200'),
			'banner' => new ArOn_Crud_Grid_Column_JoinOne("Вставлять ли баннер",array('Db_OnlineTypes'),'banner',null,false,'200'),
		);

		$this->fields['size']->options = array('1' => "big", '2' => "middle", '3' => "small");

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'showrooms_id' => new ArOn_Crud_Grid_Filter_Field_Value('showrooms_id','ID',ArOn_Db_Filter_Field::EQ),
			'companies_id' => new ArOn_Crud_Grid_Filter_Field_Value('companies_id','ID',ArOn_Db_Filter_Field::EQ),
			'types_id' => new ArOn_Crud_Grid_Filter_Field_Value('types_id','ID',ArOn_Db_Filter_Field::EQ),
			'languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('languages_id','Язык выставки',array(
				array(
					'path' => array('Db_Companies','Db_Lang_CompaniesData'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
		);
		$this->_params['languages_id'] = DEFAULT_LANG_ID.';NULL';

		parent::init();
	}
}
