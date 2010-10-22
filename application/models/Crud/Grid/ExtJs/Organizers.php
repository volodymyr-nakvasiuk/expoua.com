<?php
class Crud_Grid_ExtJs_Organizers extends ArOn_Crud_Grid_ExtJs {

	protected $_idProperty = 'id';
	public $sort = "id";
	public $direction = "ASC";
	public $editController = 'grid';
	public $editAction = 'organizers-tab';

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Организаторы';

		$this->gridActionName = 'organizers';
		$this->table = "Db_Lang_OrganizersData";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			'city' => new ArOn_Crud_Grid_Column_JoinOne("Город",array('Db_Organizers','Db_Lang_LocationCitiesData'),'name',null,false,'200'),
			'name' => new ArOn_Crud_Grid_Column_Default("Название",null,true,false,'400'),
			'phone' => new ArOn_Crud_Grid_Column_Default("Телефон",null,true,false,'100'),
			'fax' => new ArOn_Crud_Grid_Column_Default("Факс",null,true,false,'100'),
			'email' => new ArOn_Crud_Grid_Column_Default("Почта",null,true,false,'200'),
			'web_address' => new ArOn_Crud_Grid_Column_Default("Сайт",null,true,false,'200'),
			'address' => new ArOn_Crud_Grid_Column_Default("Адрес",null,true,false,'200'),
			'postcode' => new ArOn_Crud_Grid_Column_Default("Индекс",null,true,false,'200'),
			'cont_pers_name' => new ArOn_Crud_Grid_Column_Default("Контактное лицо",null,true,false,'200'),
			'cont_pers_phone' => new ArOn_Crud_Grid_Column_Default("Телефон КЛ",null,true,false,'200'),
			'cont_pers_email' => new ArOn_Crud_Grid_Column_Default("Почта КЛ",null,true,false,'200'),
			'active' => new ArOn_Crud_Grid_Column_JoinOne('Активный','Db_Organizers', 'active', null, false, '50'),
			//нельзя использовать изза языков-> 'social_organizations' => new ArOn_Crud_Grid_Column_JoinMany('Социальные организации',array('Db_SocialOrganizations2Organizers','Db_Lang_SocialOrganizationsData'),null,null,', ',5, '500'),
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
					'path' => array('Db_Organizers','Db_Lang_LocationCitiesData'),
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
