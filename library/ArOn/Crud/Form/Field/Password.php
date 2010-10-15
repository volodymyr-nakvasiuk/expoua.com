<?php
class ArOn_Crud_Form_Field_Password extends ArOn_Crud_Form_Field {

	protected $_type = 'password';
	protected $required = 1;
	protected $_covertType = 'string';

	public function updateField() {
		parent::updateField ();
		$this->element->addFilter ( 'StringTrim' )->addValidators ( array (
		array ('StringLength', false, array (6) ) ) );//->addDecorator('Label', array('tag' => 'th','requiredSuffix' => ' (*)'))

	}

	public function getInsertData() {
		if (! $this->saveInDataBase)
			return false;
		$value = $this->element->getValue ();
		if (! empty ( $value )) {
			$data = $this->prepareInsertData();
			$data ['data']['value'] = $value;
			return $data;
		}
		return false;
	}
}
