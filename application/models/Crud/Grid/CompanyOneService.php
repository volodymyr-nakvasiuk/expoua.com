<?php
class Crud_Grid_CompanyOneService extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "date_modify";
	public $direction = "DESC";
	public $editController = 'grid';
	public $editAction = 'company-one-service-tab';
	protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Товары и услуги компании';

		$this->gridActionName = 'company';
		$this->table = "Db_CompanyServices";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			'type' => new ArOn_Crud_Grid_Column_Default("Дата создания",null,true,false,'100'),
			'price' => new ArOn_Crud_Grid_Column_Default("Дата создания",null,true,false,'100'),
			'photo' => new ArOn_Crud_Grid_Column_Numeric("Дата создания",null,true,false,'100'),
			'date_modify' => new ArOn_Crud_Grid_Column_Default("Дата создания",null,true,false,'100'),

			'name' => new ArOn_Crud_Grid_Column_JoinOne("Название новости",array('Db_Lang_CompanyServicesData'),'name',null,false,'200'),
			'content' => new ArOn_Crud_Grid_Column_JoinOne("Описание новости",array('Db_Lang_CompanyServicesData'),'short',null,false,'200'),
			'companies_id' => new ArOn_Crud_Grid_Column_Numeric("Дата создания",null,true,false,'100'),
			'active' => new ArOn_Crud_Grid_Column_JoinOne("Описание новости",array('Db_Lang_CompanyServicesActive'),'active',null,false,'200'),
		);

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'id' => new ArOn_Crud_Grid_Filter_Field_Value('id','ID',ArOn_Db_Filter_Field::EQ),
			'languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('languages_id','Язык новости',array(
				array(
					'path' => array('Db_Lang_CompanyServicesData'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'active_languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('active_languages_id','Язык новости',array(
				array(
					'path' => array('Db_Lang_CompanyServicesActive'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'active' => new ArOn_Crud_Grid_Filter_Field_Search('active','Язык новости',array(
				array(
					'path' => array('Db_Lang_CompanyServicesActive'),
					'filters' => array(
						'active' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
		);
		$this->_params['active'] = 1;
		$this->_params['active_languages_id'] = DEFAULT_LANG_ID;
		$this->_params['languages_id'] = DEFAULT_LANG_ID;

		parent::init();
	}
}
