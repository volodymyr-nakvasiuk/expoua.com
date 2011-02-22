<?php
class RequestsController extends Abstract_Controller_AjaxController  {
	
	public $resultJSON = array();

	public function preDispatch() {
		parent::preDispatch();
		$this->resultJSON['success'] = true;
	}

	protected function _insertRequest($data){
		$db = Bootstrap::$registry->database;
		$stmt = $db->prepare("
			INSERT INTO
				requests
			SET
				type = :type,
				parent = :parent,
				child = :child,
				countries_id = :countries_id,
				sites_id = '". SITE_ID . "',
				languages_id = '" . DEFAULT_LANG_ID . "',
				host = INET_ATON('" . ArOn_Crud_Tools_IpCheck::getClientIp() . "')
			;"
		);
		$res = $stmt->execute(array(
			':type'         => $data['type'],
			':parent'       => $data['parent'],
			':child'        => $data['child'],
			':countries_id' => $data['countries_id'],
		));
		if ($res){
			$stmt_data = $db->prepare("
				INSERT INTO requests_data
					(requests_id, type, value)
				VALUES
					('" . $db->lastInsertId() . "', :type, :value)
				;
			");
			foreach($data['fields'] as $type=>$value){
				$res = $stmt_data->execute(array(
					':type'  => $type,
					':value' => $value,
				));
				if (!$res){
					return false;
				}
			}
			return true;
		}
		else {
			return false;
		}
	}

	protected function _validateData($fieldsRules, $data){
		$validation = array('valid'=>true, 'errors'=>array());
		foreach ($fieldsRules as $field=>$rules){
			foreach($rules as $type=>$rule){
				$class = new ReflectionClass('ArOn_Zend_Validate_' . ucfirst($type));
				$args = array($this->view->lang);
				if (is_array($rule)) {
					$args = array_merge($args, $rule);
				}
				$object = $class->newInstanceArgs($args);
				if (!$object->isValid($data[$field])){
					$validation['valid'] = false;
					if (!isset($validation['errors'][$field])) $validation['errors'][$field] = array();
					$validation['errors'][$field][] = $object->getMessages();
				}
			}
		}
		$captcha = new ArOn_Zend_Captcha_Image(array('name'=>'captcha'));
		if (!$captcha->isValid($data)){
			$validation['errors']['captcha[input]'] = array($captcha->getMessages());
		}
		return $validation;
	}

	protected function _actionInterface($fieldsAndRules, $data){
		$fieldsData = $this->_request->getPost();
		$validation = $this->_validateData($fieldsAndRules, $fieldsData);
		if ($validation['valid']){
			$data = array(
				'type'=>$data['type'],
				'parent'=>$data['parent']?intval($fieldsData[$data['parent']]):0,
				'child'=>$data['child']?intval($fieldsData[$data['child']]):0,
				'countries_id'=>$data['countries_id']?intval($fieldsData[$data['countries_id']]):0,
				'fields'=>array(),
			);
			foreach ($fieldsAndRules as $fieldName => $rules){
				$data['fields'][$fieldName] = $fieldsData[$fieldName];
			}
			if ($this->_insertRequest($data)){
				$this->resultJSON['success'] = true;
				$this->resultJSON['message'] = $this->view->lang->translate('Your request was successfully sent!');
			}
			else {
				$this->resultJSON['success'] = false;
				$this->resultJSON['message'] = $this->view->lang->translate('ERROR: Database error!');
			}
		}
		else {
			$this->resultJSON['success'] = false;
			$this->resultJSON['message'] = $this->view->lang->translate('ERROR: form is not valid!');
			$this->resultJSON['errors'] = array();
			foreach($validation['errors'] as $fieldName=>$fieldErrors){
				$this->resultJSON['errors'][$fieldName] = array();
				foreach ($fieldErrors as $errors){
					foreach ($errors as $errorKey=>$errorMessage){
						$this->resultJSON['errors'][$fieldName][] = $errorMessage;
					}
				}
			}
			$this->resultJSON['message'] .= '</dl>';
		}
	}
	
	public function exhibitionCenterInfoAction() {
		$data = array(
			'type'=>'exhibitionCenterRequest',
			'parent'=>'venue_id',
			'child'=>'venue_id',
			'countries_id'=>0,
			'fields'=>array(),
		);
		$fieldsAndRules = array(
			'name'=>array(
				'notEmpty' => true,
			),
			'contact_person'=>array(
				'notEmpty' => true,
			),
			'phone'=>array(
				'notEmpty' => true,
			),
			'email'=>array(
				'notEmpty' => true,
				'emailAddress' => true,
			),
			'address'=>array(
				'notEmpty' => true,
			),
			'message'=>array(
				'notEmpty' => true,
			),
		);
		$this->_actionInterface($fieldsAndRules, $data);
	}

	public function exhibitionExtraInfoAction() {
		$data = array(
			'type'=>'exhibitionExtraInfoRequest',
			'parent'=>'organizer_id',
			'child'=>'event_id',
			'countries_id'=>'country_id',
			'fields'=>array(),
		);
		$fieldsAndRules = array(
			'name'=>array(
				'notEmpty' => true,
			),
			'contact_person'=>array(
				'notEmpty' => true,
			),
			'phone'=>array(
				'notEmpty' => true,
			),
			'email'=>array(
				'notEmpty' => true,
				'emailAddress' => true,
			),
			'address'=>array(
				'notEmpty' => true,
			),
			'purpose'=>array(
				'notEmpty' => true,
			),
			'message'=>array(
				'notEmpty' => true,
			),
		);
		$this->_actionInterface($fieldsAndRules, $data);
	}
}
