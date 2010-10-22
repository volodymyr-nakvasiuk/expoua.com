<?php
class Crud_Grid_ExtJs_SocialOrganizationsTab extends ArOn_Crud_Grid_ExtJs_TabForm
{
	protected $modelName = 'Db_SocialOrganizations';
	
	public function init(){
		$this->ajaxActionName = 'social-organizations';
		$this->gridTitle = 'Социальная организация';
		$clear_form = false;
		if(empty($this->_params['id'])){
			$clear_form = true;
			$table = new $this->modelName;
			$insert = array('active' => 0);
			$id = $table->insert($insert);

			$this->actionId = $id;
			$this->grid_id = $id;

			$this->_params['id'] = $id;
		}
		$form = new Crud_Form_ExtJs_SocialOrganizations($this->_params['id']);
		if($clear_form) $form->clearData()->setData(array('id' => $this->_params['id']));
		$this->_tabs = array(
			$form,
			Tools_LangForm::getLangForm('Crud_Form_ExtJs_SocialOrganizationsData', array('actionId'=>$this->_params['id']), 1),
			Tools_LangForm::getLangForm('Crud_Form_ExtJs_SocialOrganizationsData', array('actionId'=>$this->_params['id']), 2),
		);
	}
}
