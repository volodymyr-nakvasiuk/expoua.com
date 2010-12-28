<?php
class Crud_Form_ExtJs_OnlinePlaces extends ArOn_Crud_Form_ExtJs
{
	protected $modelName = 'Db_OnlinePlaces';
	protected $_title = 'Онлайн выставка - настрока стенда';

	public function init ()
	{
		$this->action = '/' . self::$ajaxModuleName . '/online-places/save/';
		$this->actionName = 'online-places';

		$this->fields = array(
			'id' => new ArOn_Crud_Form_Field_Numeric('id', 'Id'),
			'region' => new ArOn_Crud_Form_Field_Many2One('regions_id','Регион', null, false),
			'country' => new ArOn_Crud_Form_Field_Array2Select('countries_id','Страна'),
			'city' => new ArOn_Crud_Form_Field_Array2Select('cities_id','Город'),
			'company' => new ArOn_Crud_Form_Field_Array2Select('companies_id', 'Компания'),
			'showroom' => new ArOn_Crud_Form_Field_Many2One('showrooms_id', 'Выставочный зал', null, false),
			'type' => new ArOn_Crud_Form_Field_Many2One('types_id', 'Тип', null, false),
			'left' => new ArOn_Crud_Form_Field_Numeric('left', 'Отступ по горизонтали') ,
			'top' => new ArOn_Crud_Form_Field_Numeric('top', 'Отступ по вертикали') ,
			'showrooms_order' => new ArOn_Crud_Form_Field_Numeric('showrooms_order', 'Номер в зале') ,
		);
		$this->fields['region']->model = 'Db_Lang_LocationRegionsData';

		$this->fields['region']->onchange = "function( combo, record, index ) {
																var form = ".$this->getItem().".getForm();
																var value = record.get('optionValue');
																var resource = form.items.get('".$this->actionName."-country-id-".$this->actionId."');
																var store = resource.getStore();
																store.baseParams = store.baseParams || {};
																store.baseParams['parent_id'] = value;
																store.load();
																resource.clearValue();
																}";

		$this->fields['country']->setOptions(array());
		$this->fields['country']->setElementHelper('formSelectAutoLoad');
		$this->fields['country']->addAttrib('actionId', $this->actionId);
		$this->fields['country']->addAttrib('actionUrl', 'lang_location-countries-data');

		$this->fields['country']->onchange = "function( combo, record, index ) {
																var form = ".$this->getItem().".getForm();
																var value = record.get('optionValue');
																var resource = form.items.get('".$this->actionName."-city-id-".$this->actionId."');
																var store = resource.getStore();
																store.baseParams = store.baseParams || {};
																store.baseParams['parent_id'] = value;
																store.load();
																resource.clearValue();
																}";

		$this->fields['city']->setOptions(array());
		$this->fields['city']->setElementHelper('formSelectAutoLoad');
		$this->fields['city']->addAttrib('actionId', $this->actionId);
		$this->fields['city']->addAttrib('actionUrl', 'lang_location-cities-data');

		$this->fields['city']->onchange = "function( combo, record, index ) {
																var form = ".$this->getItem().".getForm();
																var value = record.get('optionValue');
																var resource = form.items.get('".$this->actionName."-company-id-".$this->actionId."');
																var store = resource.getStore();
																store.baseParams = store.baseParams || {};
																store.baseParams['parent_id'] = value;
																store.load();
																resource.clearValue();
																}";

		$this->fields['company']->setOptions(array());
		$this->fields['company']->setElementHelper('formSelectAutoLoad');
		$this->fields['company']->addAttrib('actionId', $this->actionId);
		$this->fields['company']->addAttrib('actionUrl', 'lang_companies2-data');

		if(!empty($this->actionId) && $this->_data ['Db_OnlinePlaces.companies_id']){
			$model = Db_Companies2::getInstance();
			$model = $model->fetchRow($model->getPrimary()." = ".$this->_data ['Db_OnlinePlaces.companies_id']);
			$city = $model[ 'cities_id' ];
			$this->fields['company']->addAttrib('parent_id',$city);
			$this->fields['company']->setValue($model ['id']);

			$model = Db_LocationCities::getInstance();
			$model = $model->fetchRow($model->getPrimary()." = ".$city);
			$country = $model [ 'countries_id' ];
			$this->fields['city']->addAttrib('parent_id',$country);
			$this->fields['city']->setValue($model ['id']);
			//$this->fields['country']->setValue($country);

			$model = Db_LocationCountries::getInstance();
			$model = $model->fetchRow($model->getPrimary()." = ".$country);
			$region = $model [ 'regions_id' ];
			$this->fields['country']->addAttrib('parent_id',$region);
			$this->fields['country']->setValue($model ['id']);

			$this->fields['region']->setValue($region);
		}

		//$this->fields['company']->model = 'Db_Lang_CompaniesData';
		$this->fields['showroom']->model = 'Db_OnlineShowRooms';
		$this->fields['type']->model = 'Db_OnlineTypes';

		if(empty($this->actionId)) unset($this->fields['id']);
		else $this->fields['id']->setElementHelper('formNotEdit');

		$this->groups = array('0' => array_keys($this->fields));
		parent::init();
	}

	public function saveValidData(){
		$presave_data = $this->getData();
		if (!$presave_data['company']){
			$this->fields['company']->noSave();
		}
		parent::saveValidData();
	}
}
