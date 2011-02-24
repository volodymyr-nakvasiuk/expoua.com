<?php
class Crud_Grid_Organizer extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "id";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'organizers-tab';
	protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Организатор';
		//$this->where = 'active=1';

		$this->gridActionName = 'organizers';
		$this->table = "Db_Organizers";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			//'languages_id' => new ArOn_Crud_Grid_Column_JoinOne("Язык выставки",array('Db_Lang_EventsData'),'languages_id',null,false,'200'),
			'description' => new ArOn_Crud_Grid_Column_JoinOne("Описание выставки",array('Db_Lang_OrganizersData'),'description',null,false,'200'),

			'address' => new ArOn_Crud_Grid_Column_JoinOne("Адрес",array('Db_Lang_OrganizersData'),'address',null,false,'200'),
			'postcode' => new ArOn_Crud_Grid_Column_JoinOne("Индекс",array('Db_Lang_OrganizersData'),'postcode',null,false,'200'),
			'phone' => new ArOn_Crud_Grid_Column_JoinOne("Телефон",array('Db_Lang_OrganizersData'),'phone',null,false,'200'),
			'fax' => new ArOn_Crud_Grid_Column_JoinOne("Факс",array('Db_Lang_OrganizersData'),'fax',null,false,'200'),
			'email' => new ArOn_Crud_Grid_Column_JoinOne("E-mail",array('Db_Lang_OrganizersData'),'email',null,false,'200'),
			'web_address' => new ArOn_Crud_Grid_Column_JoinOne("Веб сайт",array('Db_Lang_OrganizersData'),'web_address',null,false,'200'),

			//'brands_languages_id' => new ArOn_Crud_Grid_Column_JoinOne("Язык бренда",array('Db_Lang_BrandsData'),'languages_id',null,false,'200'),
			'organizer' => new ArOn_Crud_Grid_Column_JoinOne("Название компании",array('Db_Lang_OrganizersData'),'name',null,false,'200'),
			'name' => new ArOn_Crud_Grid_Column_JoinOne("Название компании",array('Db_Lang_OrganizersData'),'name',null,false,'200'),
			'regions_id' => new ArOn_Crud_Grid_Column_JoinOne("Регион",array('Db_LocationCities','Db_LocationCountries'),'regions_id',null,false,'200'),
			'countries_id' => new ArOn_Crud_Grid_Column_JoinOne("Страна",array('Db_LocationCities'),'countries_id',null,false,'200'),
			'cities_id' => new ArOn_Crud_Grid_Column_Numeric("Город",null,true,false,'50'),
			//'active' => new ArOn_Crud_Grid_Column_Numeric('Активный',null,true,false,'50'),
		);
		//$this->fields['active']->options = array('1'=>"+", '0'=>"-");

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'id' => new ArOn_Crud_Grid_Filter_Field_Value('id','ID',ArOn_Db_Filter_Field::EQ),
			'languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('languages_id','Язык выставки',array(
				array(
					'path' => array('Db_Lang_OrganizersData'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'active' => new ArOn_Crud_Grid_Filter_Field_Value('active','Активный',ArOn_Db_Filter_Field::EQ),
		);
		$this->_params['languages_id'] = DEFAULT_LANG_ID;
		$this->_params['active'] = 1;

		parent::init();
	}
}
