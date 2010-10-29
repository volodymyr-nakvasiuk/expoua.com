<?php
class Crud_Form_ExtJs_Organizers extends Crud_Form_ExtJs_Tabitem {

	protected $modelName = 'Db_Organizers';
	protected $_title = 'Все языки';

	public function init () {
		$this->action = '/' . self::$ajaxModuleName . '/organizers/save/';
		$this->actionName = 'organizers';

		$this->fields = array(
			'id' => new ArOn_Crud_Form_Field_Numeric('id', 'Id', null, true, true),
			'region' => new ArOn_Crud_Form_Field_Many2One('regions_id','Регион'),
			'country' => new ArOn_Crud_Form_Field_Array2Select('countries_id','Страна'),
			'city' => new ArOn_Crud_Form_Field_Array2Select('cities_id','Город'),
			'active' => new ArOn_Crud_Form_Field_Array2Select('active', 'Статус', null, true),
			'social_organizations' => new ArOn_Crud_Form_Field_Many2Many('social_organizations','Социальные организации')
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

		if(!empty($this->actionId)){
			$model = Db_LocationCities::getInstance();
			$model = $model->fetchRow($model->getPrimary()." = ".$this->_data ['Db_Organizers.cities_id']);
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

		$this->fields['active']->setOptions(array('1' => "Активный", '0' => "Не активный"));
		if(empty($this->actionId)) unset($this->fields['id']);
		else $this->fields['id']->setElementHelper('formNotEdit');

		$this->fields['social_organizations']->helper = array(
			'model' => 'Db_Lang_SocialOrganizationsData',
			'workingModel' => 'Db_SocialOrganizations2Organizers'
		);
		$this->fields['social_organizations']->setElementHelper('formMultiSelect');
		$this->fields['social_organizations']->setExplode(',');

		$this->groups = array('0' => array_keys($this->fields));
		parent::init();
	}
}
