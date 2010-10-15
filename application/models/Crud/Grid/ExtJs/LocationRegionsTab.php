<?php
class Crud_Grid_ExtJs_LocationRegionsTab extends ArOn_Crud_Grid_ExtJs_TabForm
{
	protected $modelName = 'Db_LocationRegions';
	
	public function init(){
		$this->ajaxActionName = 'location-regions';
		$this->gridTitle = 'Регион';
		$this->_tabs = array(
			new Crud_Form_ExtJs_LocationRegions($this->_params['id']),
			Tools_LangForm::getLangForm('Crud_Form_ExtJs_LocationRegionsData', array('actionId'=>$this->_params['id']), 1),
			Tools_LangForm::getLangForm('Crud_Form_ExtJs_LocationRegionsData', array('actionId'=>$this->_params['id']), 2),
		);
	}
}
