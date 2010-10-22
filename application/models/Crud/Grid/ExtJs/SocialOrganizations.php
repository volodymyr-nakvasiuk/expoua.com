<?php
class Crud_Grid_ExtJs_SocialOrganizations extends ArOn_Crud_Grid_ExtJs {

	protected $_idProperty = 'id';
	public $sort = "id";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'social-organizations-tab';

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Социальные организации';

		$this->gridActionName = 'social-organizations';
		$this->table = "Db_Lang_SocialOrganizationsData";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			'city' => new ArOn_Crud_Grid_Column_JoinOne("Город",array('Db_SocialOrganizations','Db_Lang_LocationCitiesData'),'name',null,false,'200'),
			'name' => new ArOn_Crud_Grid_Column_Default("Название",null,true,false,'400'),
			'phone' => new ArOn_Crud_Grid_Column_Default("Телефон",null,true,false,'100'),
			'fax' => new ArOn_Crud_Grid_Column_Default("Факс",null,true,false,'100'),
			'email' => new ArOn_Crud_Grid_Column_Default("Почта",null,true,false,'200'),
			'web_address' => new ArOn_Crud_Grid_Column_Default("Сайт",null,true,false,'200'),
			'address' => new ArOn_Crud_Grid_Column_Default("Адрес",null,true,false,'200'),
			'postcode' => new ArOn_Crud_Grid_Column_Default("Индекс",null,true,false,'200'),
			'active' => new ArOn_Crud_Grid_Column_JoinOne('Активный','Db_SocialOrganizations', 'active', null, false, '50'),
		);
		$this->fields['active']->options = array('1'=>"+", '0'=>"-");

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'search' => new ArOn_Crud_Grid_Filter_Field_Search('search','Search:', array(
				array(
					'path' => null,
					'filters' => array(
						ArOn_Db_Filter_Search::ID => ArOn_Db_Filter_Search::EQ,
						ArOn_Db_Filter_Search::NAME => ArOn_Db_Filter_Search::LIKE
					),
				),
				array(
					'path' => array('Db_SocialOrganizations','Db_Lang_LocationCitiesData'),
					'filters' => array(
						ArOn_Db_Filter_Search::NAME => ArOn_Db_Filter_Search::LIKE
					),
				),
			)),
			'id' => new ArOn_Crud_Grid_Filter_Field_Value('id', 'id:',ArOn_Db_Filter_Field::EQ),
		);

		parent::init();
	}
}
