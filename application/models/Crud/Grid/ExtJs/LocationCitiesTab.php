<?php
class Crud_Grid_ExtJs_LocationCitiesTab extends ArOn_Crud_Grid_ExtJs_TabForm
{
	protected $modelName = 'Db_LocationCities';
	
	public function init(){
		$this->ajaxActionName = 'location-cities';
		$this->gridTitle = 'Город';
		$this->_tabs = array(
			new Crud_Form_ExtJs_LocationCities($this->_params['id']),
			//Tools_LangForm::getLangForm('Crud_Form_ExtJs_LocationCitiesData', array('actionId'=>$this->_params['id']), 1),
			//Tools_LangForm::getLangForm('Crud_Form_ExtJs_LocationCitiesData', array('actionId'=>$this->_params['id']), 2),
		);
	}
}
