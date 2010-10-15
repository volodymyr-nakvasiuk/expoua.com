<?php
class Crud_Grid_ExtJs_LocationCountriesTab extends ArOn_Crud_Grid_ExtJs_TabForm
{
	protected $modelName = 'Db_LocationCountries';
	
	public function init(){
		$this->ajaxActionName = 'location-countries';
		$this->gridTitle = 'Страна';
		$this->_tabs = array(
			new Crud_Form_ExtJs_LocationCountries($this->_params['id']),
			Tools_LangForm::getLangForm('Crud_Form_ExtJs_LocationCountriesData', array('actionId'=>$this->_params['id']), 1),
			Tools_LangForm::getLangForm('Crud_Form_ExtJs_LocationCountriesData', array('actionId'=>$this->_params['id']), 2),
		);
	}
}
