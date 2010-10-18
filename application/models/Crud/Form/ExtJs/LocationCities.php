<?php
class Crud_Form_ExtJs_LocationCities extends Crud_Form_ExtJs_Tabitem {

	protected $modelName = 'Db_LocationCities';
	protected $_title = 'Все языки';

	public function init () {
		$this->action = '/' . self::$ajaxModuleName . '/location-cities/save/';
		$this->actionName = 'location-cities';

		$this->fields = array(
			'id' => new ArOn_Crud_Form_Field_Numeric('id', 'Id', null, true, true),
			'region' => new ArOn_Crud_Form_Field_Many2One('regions_id','Регион'),
			'country' => new ArOn_Crud_Form_Field_Array2Select('countries_id','Ресурс'),
			'geonameid' => new ArOn_Crud_Form_Field_Text('geonameid', 'http://geonames.org/', null, true),
			'active' => new ArOn_Crud_Form_Field_Array2Select('active', 'Статус', null, true)
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

		if(!empty($this->actionId)){
			$model = Db_LocationCountries::getInstance();
			$model = $model->fetchRow($model->getPrimary()." = ".$this->_data ['Db_LocationCities.countries_id']);
			$region = $model [ 'regions_id' ];
			$this->fields['country']->addAttrib('parent_id',$region);
			$this->fields['country']->setValue($model ['id']);
			$this->fields['region']->setValue($region);
		}



		$this->fields['active']->setOptions(array('1' => "Активный", '0' => "Не активный"));
		if(empty($this->actionId)) unset($this->fields['id']);
		else $this->fields['id']->setElementHelper('formNotEdit');

		$this->groups = array('0' => array_keys($this->fields));
		parent::init();
	}
}
