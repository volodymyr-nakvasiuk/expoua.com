<?php
class Crud_Grid_CompanyNews extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "date_public";
	public $direction = "DESC";
	public $editController = 'grid';
	public $editAction = 'company-news-tab';
	//protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Новости компании';

		$this->gridActionName = 'company';
		$this->table = "Db_CompanyNews";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			'date_public' => new ArOn_Crud_Grid_Column_Default("Дата",null,true,false,'100'),
			'date_modify' => new ArOn_Crud_Grid_Column_Default("Дата создания",null,true,false,'100'),
			//'logo' => new ArOn_Crud_Grid_Column_Numeric("Дата создания",null,true,false,'100'),
			//'languages_id' => new ArOn_Crud_Grid_Column_JoinOne("Язык новости",array('Db_Lang_EventNewsData'),'languages_id',null,false,'200'),
			'name' => new ArOn_Crud_Grid_Column_JoinOne("Название новости",array('Db_Lang_CompanyNewsData'),'name',null,false,'200'),
			'content' => new ArOn_Crud_Grid_Column_JoinOne("Описание новости",array('Db_Lang_CompanyNewsData'),'preambula',null,false,'200'),
			//'content' => new ArOn_Crud_Grid_Column_JoinOne("Текст новости",array('Db_Lang_EventNewsData'),'content',null,false,'200'),
			//'active' => new ArOn_Crud_Grid_Column_JoinOne("Активна ли новость на этом языке",array('Db_Lang_EventNewsData'),'active',null,false,'200'),
		);

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('languages_id','Язык новости',array(
				array(
					'path' => array('Db_Lang_CompanyNewsData'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'companies_id' => new ArOn_Crud_Grid_Filter_Field_Value('companies_id','ID',ArOn_Db_Filter_Field::EQ),
		);
		$this->_params['languages_id'] = DEFAULT_LANG_ID;

		parent::init();
	}
}
