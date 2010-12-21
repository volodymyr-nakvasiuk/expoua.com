<?php
class Crud_Grid_CompanyOneNews extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "date_public";
	public $direction = "DESC";
	public $editController = 'grid';
	public $editAction = 'company-one-news-tab';
	protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Новости компании';

		$this->gridActionName = 'company';
		$this->table = "Db_CompanyNews";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			'date_public' => new ArOn_Crud_Grid_Column_Default("Дата",null,true,false,'100'),
			'date_modify' => new ArOn_Crud_Grid_Column_Default("Дата создания",null,true,false,'100'),
			'logo' => new ArOn_Crud_Grid_Column_Numeric("Дата создания",null,true,false,'100'),
			//'languages_id' => new ArOn_Crud_Grid_Column_JoinOne("Язык новости",array('Db_Lang_EventNewsData'),'languages_id',null,false,'200'),
			'name' => new ArOn_Crud_Grid_Column_JoinOne("Название новости",array('Db_Lang_CompanyNewsData'),'name',null,false,'200'),
			'content' => new ArOn_Crud_Grid_Column_JoinOne("Описание новости",array('Db_Lang_CompanyNewsData'),'content',null,false,'200'),
			//'content' => new ArOn_Crud_Grid_Column_JoinOne("Текст новости",array('Db_Lang_EventNewsData'),'content',null,false,'200'),
			'active' => new ArOn_Crud_Grid_Column_JoinOne("Описание новости",array('Db_Lang_CompanyNewsActive'),'active',null,false,'200'),
		);

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'id' => new ArOn_Crud_Grid_Filter_Field_Value('id','ID',ArOn_Db_Filter_Field::EQ),
			'languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('languages_id','Язык новости',array(
				array(
					'path' => array('Db_Lang_CompanyNewsData'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'active_languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('active_languages_id','Язык новости',array(
				array(
					'path' => array('Db_Lang_CompanyNewsActive'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'active' => new ArOn_Crud_Grid_Filter_Field_Search('active','Язык новости',array(
				array(
					'path' => array('Db_Lang_CompanyNewsActive'),
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
