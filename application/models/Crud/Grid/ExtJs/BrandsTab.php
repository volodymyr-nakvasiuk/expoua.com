<?php
class Crud_Grid_ExtJs_BrandsTab extends ArOn_Crud_Grid_ExtJs_TabForm
{
	protected $modelName = 'Db_Brands';
	
	public function init(){
		$this->ajaxActionName = 'brands';
		$this->gridTitle = 'Бренд';
		$clear_form = false;
		if(empty($this->_params['id'])){
			$clear_form = true;
			$table = new $this->modelName;
			$insert = array('dead' => 1);
			$id = $table->insert($insert);

			$this->actionId = $id;
			$this->grid_id = $id;

			$this->_params['id'] = $id;
		}
		$form = new Crud_Form_ExtJs_Brands($this->_params['id']);
		if($clear_form) $form->clearData()->setData(array('id' => $this->_params['id']));
		$this->_tabs = array(
			$form,
			Tools_LangForm::getLangForm('Crud_Form_ExtJs_BrandsData', array('actionId'=>$this->_params['id']), 1),
			Tools_LangForm::getLangForm('Crud_Form_ExtJs_BrandsData', array('actionId'=>$this->_params['id']), 2),
		);
	}
}
