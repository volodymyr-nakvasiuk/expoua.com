<?php
class Crud_Grid_EventOneNews extends ArOn_Crud_Grid {

	protected $_idProperty = 'id';
	public $sort = "date_public";
	public $direction = "DESC";
	public $editController = 'grid';
	public $editAction = 'event-one-news-tab';
	protected $_ifCount = false;

	public function init() {
		$this->trash = false;
		$this->gridTitle = 'Новость выставки';

		$this->gridActionName = 'news';
		$this->table = "Db_EventNews";
		$this->fields = array(
			'id' => new ArOn_Crud_Grid_Column_Numeric('Id',null,true,false,'50'),
			//'date_public' => new ArOn_Crud_Grid_Column_Default("Дата",null,true,false,'100'),
			//'date_created' => new ArOn_Crud_Grid_Column_Default("Дата создания",null,true,false,'100'),
			//'languages_id' => new ArOn_Crud_Grid_Column_JoinOne("Язык новости",array('Db_Lang_EventNewsData'),'languages_id',null,false,'200'),
			'name' => new ArOn_Crud_Grid_Column_JoinOne("Название новости",array('Db_Lang_EventNewsData'),'name',null,false,'200'),
			//'preambula' => new ArOn_Crud_Grid_Column_JoinOne("Описание новости",array('Db_Lang_EventNewsData'),'preambula',null,false,'200'),
			'content' => new ArOn_Crud_Grid_Column_JoinOne("Текст новости",array('Db_Lang_EventNewsData'),'content',null,false,'200'),
			//'active' => new ArOn_Crud_Grid_Column_JoinOne("Активна ли новость на этом языке",array('Db_Lang_EventNewsData'),'active',null,false,'200'),
		);

		$this->filters->setPrefix(false);
		$this->filters->fields = array(
			'id' => new ArOn_Crud_Grid_Filter_Field_Value('id','ID',ArOn_Db_Filter_Field::EQ),
			'languages_id' => new ArOn_Crud_Grid_Filter_Field_Search('languages_id','Язык новости',array(
				array(
					'path' => array('Db_Lang_EventNewsData'),
					'filters' => array(
						'languages_id' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			'active' => new ArOn_Crud_Grid_Filter_Field_Search('active','Активна ли новость на этом языке',array(
				array(
					'path' => array('Db_Lang_EventNewsData'),
					'filters' => array(
						'active' => ArOn_Db_Filter_Search::EQ,
					),
				),
			)),
			//'brands_id' => new ArOn_Crud_Grid_Filter_Field_Value('brands_id','ID',ArOn_Db_Filter_Field::EQ),
		);
		$this->_params['active'] = 1;
		$this->_params['languages_id'] = DEFAULT_LANG_ID;

		parent::init();
	}
}
