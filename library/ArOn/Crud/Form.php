<?php
//require_once 'Zend/Form.php';

class ArOn_Crud_Form extends Zend_Form {

	protected $modelName;

	/**
	 * @var ArOn_Db_Table
	 */
	public $_model;

	public $groups = array ();

	public $fields = array ();

	public $method = 'post';

	public $action = '';

	protected $groupNames = array ();

	public $actionId;

	public $rowCount;

	protected $_data = array ();
	protected $_load_data = array();
	protected $_def_data = array ();

	protected $_alternative_data = array ();
	protected $filterPrefix = false;
	protected $_isLoadData = false;
	public $ajax = false;

	public $grid_id;

	public static $ajaxModuleName;
	public static $ajaxActionName;
	
	protected $actionName;
	
	public $submitButton = true;

	public $is_new_data = false;
	public $uploadDirectory;
	protected $filterParams;
	public $template = false;
	protected $template_id;
	public $template_fields = array ();
	public $default = array ();

	/**
	 * Decorator flag
	 * @var bool
	 */
	protected $_decorate;

	/**
	 * Decorator class
	 * @var ArOn_Crud_Form_Decorator
	 */
	protected $_decorateClass;

	protected $_disableLoadDefaultDecorators = true;
	protected $_translatorDisabled = true;

	public function __construct($actionId = null, $filter_params = false, $template = false , $options = null, $action = null, $decorate = true , $decorateClass = 'ArOn_Crud_Form_Decorator_Admin') {
		$this->actionId = $actionId;
		$this->filterParams = $filter_params;
		$this->_decorate = $decorate;
		$this->template = $template;
		if($this->_decorate) {
			$this->_decorateClass = new $decorateClass ( );
		}
		$this->initModel();
		if ($this->actionId != null) {
			$this->loadData ( $this->actionId );
		}
		$this->grid_id = $actionId;
		if (! empty ( $action ))
			$this->action = $action;
		
		parent::__construct ( $options );
		ArOn_Crud_Tools_Register::registerData();

		$this->setup();
	}

	public function initModel() {
		if ($this->modelName){
			if (!is_array($this->modelName)) $this->modelName = array($this->modelName);
			$this->_model = array();
			foreach ($this->modelName as $modelName){
				$this->_model[] = ArOn_Crud_Tools_Registry::singleton ( $modelName );
			}
		}
		else $this->_model = array(ArOn_Crud_Tools_Registry::singleton ( 'ArOn_Db_Table' ));
	}
	public function init() {
		foreach ( $this->fields as $name => $field ) {
			$field->setForm ( $this );
			$field->setElementFormName($name)->setPrefixFilter($this->filterPrefix);
			$field->setAjaxActionName($this->actionName);
			$field->setTableName ( $this->getModelByName($field->getModelName())->info ( Zend_Db_Table::NAME ) );
		}
	}

	
	protected function setup(){
		$this->setAttrib ( 'module', ArOn_Crud_Grid::$ajaxModuleName );
		$this->setAttrib ( 'id', 'form_' . $this->grid_id );
		
		if(empty($this->actionName)) $this->actionName = self::$ajaxActionName;
	}
	
	public function createForm() {
		
		if (! is_array ( $this->groups )) {
			return false;
		}
		
		foreach ( $this->fields as $name => $field ) {
			if (is_object ( $field ) && $field instanceof ArOn_Crud_Form_Field) {
				
				if ($this->template) {
					$field->addHelper ( 'id', $this->template_id );
				} else {
					$field->addHelper ( 'id', $this->actionId );
				}
				if (isset ( $this->default [$name] )) {
					$field->default = $this->default [$name];
				}
				$element = $field->createElement ();
				$fieldNames [$name] = $element->getName ();
				$this->addElement ( $element );
				$element = NULL;
			} else {
				unset ( $this->fields [$name] );
				//$this->addElement($field);
			}
				
		}
		foreach ( $this->groups as $groupName => $fields ) {
				
			$this->groupNames [] = $groupName;
			$groupNames = array ();
			foreach ( $fields as $name ) {
				$groupNames [] = @$fieldNames [$name];
			}
			if($this->_decorate) $this->_decorateClass->decorateGroup ( $this, $groupNames, $groupName );

		}
		if (! empty ( $this->actionId )) {
			if (! empty ( $this->action ) and substr ( $this->action, strlen ( $this->action ) - 1, 1 ) != '/')
			$this->action .= '/';
			$this->action .= $this->actionId;
		}
		if ($this->filterParams) {
			/*$params = new Zend_Form_Element_Hidden('filter_params');
			 $params->setValue(http_build_query($this->filterParams));
			 $this->addElement($params);*/
			$this->action .= '?' . http_build_query($this->filterParams);
		}

		if ($this->submitButton)
		$this->addElement ( new Zend_Form_Element_Submit ( 'Save' ) );

		$this->updateForm ();

		$this->setAction ( $this->action )->setMethod ( $this->method );
		$this->setData ( false, $this->_isLoadData );
		$this->_isLoadData = false;
		if($this->filterParams)
			$this->loadFilters ();
	}

	public function setActionId($id) {
		$this->actionId = $id;
		$this->grid_id = $id;
	}

	public function setData($data = null, $nameFromDbTable = false) {
		if($data === false) $data = $this->_data;
		$this->_data = array ();
		if (empty ( $this->fields )){
			foreach ($data as $modelName=>$modelData){
				foreach ($modelData as $modelField=>$fieldData){
					$this->_data[$modelName.'.'.$modelField] = $fieldData;
				}
			}
			return $this;
		}
		foreach ( $this->fields as $field ) {
			if (!($field instanceof ArOn_Crud_Form_Field))
				continue;
			$dataName =  ($nameFromDbTable) ? $field->getName() : $field->getElementFormName();
			$formName = $field->getElementFormName();
			$modelName = $field->getModelName();
			if ($modelName && array_key_exists($modelName,$data)) {
				$tmpData = $data[$modelName];
			}
			else {
				$tmpData = $data;
				$dataName =($nameFromDbTable) ? $modelName.'.'.$dataName : $dataName;
			}
			if (array_key_exists($dataName,$tmpData)){
				if($this->template && !in_array($formName,$this->template_fields)) continue;
				$this->_data [$formName] = $tmpData[$dataName];
				if ($field instanceof ArOn_Crud_Form_Field) {
					$field->setValue ( $tmpData[$dataName] );
				}
			}
		}
		return $this;
	}
	
	public function clearData() {
		$this->_data = array ();
		if (empty ( $this->fields ))
		return $this;
		foreach ( $this->fields as $field ) {
			if ($field instanceof ArOn_Crud_Form_Field)
				$field->setValue ( null );
		}
		return $this;
	}
	
	public function loadFilters($params = false) {
		if($params !== false) $this->filterParams = $params;
		$filterData = ($this->filterPrefix) ? $this->filterParams [$this->filterPrefix] : $this->filterParams;
		$this->updateData ( $filterData, false );
		return $this;
	}
	
	public function getFilterParam($name){
		if(!$this->filterParams)
			return false;
		$filterData = ($this->filterPrefix) ? $this->filterParams [$this->filterPrefix] : $this->filterParams;
		return ArOn_Crud_Tools_Array::arraySearchRecursive($name, $filterData, false, array() , true);
	}
	
	public function loadData($id, $whereKey=false, $loadDb=false) {
		if (! $this->template) {
			$this->actionId = $id;
			$this->grid_id = $id;
		} else {
			$this->template_id = $id;
		}
		
		$data = array();
		if ($whereKey){
			$fn = 'fetchRow';
			$fp = array(str_replace('``', '`', "`".$whereKey."`='".$id."'"));
		}
		else{
			$fn = 'getRowById';
			$fp = array($id);
		}
		if ($loadDb){
			$model = $this->getModel($loadDb);
			$models = $model?array($model):array();
		}
		else {
			$models = $this->_model;
		}
		foreach($models as $model){
			$row = call_user_func_array(array($model, $fn), $fp);
			if ($row) $data[get_class($model)] = is_array($row)?$row:$row->toArray();
		}
		
		$this->_isLoadData = true;
		$this->_load_data = $data;
		$this->setData ( $data, true );
	}

	public function updateData($data, $nameFromDbTable = false) {
		if (empty ( $data ))
		return;
		foreach ( $data as $name => $value ) {
			$this->_data [$name] = $value;
		}
		$this->setData ( false , $nameFromDbTable );
	}

	public function getData() {
		$data = array();
		foreach ($this->fields as $key => $field){
			$data[$key] = $field->getValue();
		}
		$data = array_merge($data,$this->_alternative_data);
		return $data;
	}
	
	public function getLoadData($id = false, $where_key = null){
		if(!empty($id))
			$this->loadData($id,$where_key);
		return $this->_load_data;
	}
	
	public function getRenderData() {
		$data = array();
		foreach ($this->fields as $key => $field){
			$data[$key] = $field->getRenderValue();
		}
		$data = array_merge($data,$this->_alternative_data);
		return $data;
	}
	
	public function getFieldsTitle() {
		$titles = array();
		foreach ($this->fields as $key => $field){
			$titles [$key] = $field->getTitle();
		}		
		return $titles;
	}
	
	public function addAlternativeDatas(array $data) {
		foreach($data as $key => $value)
			$this->addAlternativeData($key,$value);
		return $this;
	}
	
	public function addAlternativeData($key,$value) {
		$this->_alternative_data[$key] = $value;
		return $this;
	}

	public function setAlternativeData(array $data) {
		$this->_alternative_data = $data;
		return $this;
	}

	public function addData($data) {
		if (empty ( $data ))
		return;
		foreach($data as $name => $value){
			if(array_key_exists($name,$this->fields)){
				$this->_data [$name] = $value;
				$this->fields [ $name ] -> setValue($value);
			}
		}


	}

	public function saveData($params) {
		if ($this->isValid ( $params )) {
			/*$enctype = $this->getAttrib ( 'enctype' );
			if('multipart/form-data' == $enctype){
			 $adapter = new Zend_File_Transfer_Adapter_Http();
			 $adapter->setDestination($this->uploadDirectory);
			 if (!$adapter->receive()) {
			 $messages = $adapter->getMessages();
			 echo implode("\n", $messages);die;
			 }
			}*/
			$this->setData ( $params );
				
			$result = $this->saveValidData ();
				
			return $result;
		} else {
			return false;
		}
	}

	protected function preSave() {
		foreach ( $this->fields as $name => $field ) {
			if (is_object ( $field ) && $field instanceof ArOn_Crud_Form_Field){ 
				$field->preSaveAction ( $this->_data );
			}
		}
	}

	protected function postSave() {
		foreach ( $this->fields as $field ) {
			$field->postSaveAction ( $this->_data );
		}
	}

	public function saveValidData() {
		$this->preSave ();
		$def_data = array ();
		$alter = array ();
		foreach ( $this->fields as $name => $field ) {
			$d = $field->getInsertData ();
			if (! $d) {
				$alter [] = $name;
				continue;
			}
			if (!is_bool($d)){
				if ($d['model'] == 'default') $d['model'] = $this->modelName[0];
				if (!isset($def_data[$d['model']])) $def_data[$d['model']] = array();
				$def_data[$d['model']][$d ['data'] ['key']] = $d ['data'] ['value'];
			}
		}
		if (! empty ( $this->_alternative_data )){
			foreach ($this->_alternative_data as $key=>$data){
				if (!is_array($data)) $data = array('model'=>$this->modelName[0], 'data'=>array($key=>$data));
				if (!isset($data['model']) || !$data['model'] || $data['model']=='default') $data['model'] = $this->modelName[0];
				if (!isset($def_data[$data['model']])) $def_data[$data['model']] = array();
				if (isset($data['data']) && is_array($data['data'])){
					$def_data[$data['model']] = array_merge ( $def_data[$data['model']], $data['data']);
				}
			}
		}
		//print_r($def_data);exit;
		if (!empty($this->actionId)) {
			foreach ($this->_model as $model){
				$modelName = get_class($model);
				if (isset($def_data[$modelName])){
					try {
						$this->rowCount = $model->update($def_data[$modelName], $model->getAdapter()->quoteInto($model->getPrimary()."=?", $this->actionId));
					} catch ( Exception $e ) {
						return array ('error' => $e->getMessage() );
					}
				}
			}
		}
		else {
			$models = $this->_model;
			$model = array_shift($models);
			$modelName = get_class($model);
			if (isset($def_data[$modelName])){
				try {
					$this->actionId = $model->insert($def_data[$modelName]);
					$this->is_new_data = true;
				} catch ( Exception $e ) {
					return array ('error' => $e->getMessage () );
				}
			}
			if ($this->actionId){
				foreach ($models as $model){
					$modelName = get_class($model);
					if (isset($def_data[$modelName])){
						try {
							$def_data[$modelName][$model->getPrimary()] = $this->actionId;
							$model->insert($def_data[$modelName]);
						} catch ( Exception $e ) {
							try {
								$this->rowCount = $model->update($def_data[$modelName], $model->getAdapter()->quoteInto($model->getPrimary()."=?", $this->actionId));
							} catch ( Exception $e ) {
								return array ('error' => $e->getMessage () );
							}
						}
					}
				}
			}
		}
		foreach ( $alter as $name ) {
			$this->fields [$name]->id = $this->actionId;
			$this->fields [$name]->getInsertData ();
		}
		$this->_data ['id'] = $this->actionId;
		$this->_def_data = $def_data;
		$this->postSave ();

		return $this->actionId;
	}

	public function update($id, array $data) {
		$valid = true;
		$errors = array ();
		$def_data = array();
		foreach ( $data as $name => $value ) {
			$element = $this->getElement ( $name );
			if (! $element) {
				unset ( $data [$name] );
				continue;
			}
			if ($element->isValid ( $value ) !== true) {
				$valid = false;
				$errors [$name] = implode ( ",", $element->getMessages () );
			} else {
				$field = $this->getFieldByColumnName ( $name );
				$d = $field->getInsertData ();
				if (! $d) {
					$alter [] = $name;
					continue;
				}
				if (!is_bool($d)){
					if ($d['model'] == 'default') $d['model'] = $this->modelName[0];
					if (!isset($def_data[$d['model']])) $def_data[$d['model']] = array();
					$def_data[$d['model']][$d ['data'] ['key']] = $d ['data'] ['value'];
				}
			}
		}
		if ($valid) {
			if (! empty ( $this->_alternative_data )){
				foreach ($this->_alternative_data as $key=>$data){
					if (!is_array($data)) $data = array('model'=>$this->modelName[0], 'data'=>array($key=>$data));
					if (!isset($data['model']) || !$data['model'] || $data['model']=='default') $data['model'] = $this->modelName[0];
					if (!isset($def_data[$data['model']])) $def_data[$data['model']] = array();
					if (isset($data['data']) && is_array($data['data'])){
						$def_data[$data['model']] = array_merge ( $def_data[$data['model']], $data['data']);
					}
				}
			}
			$result = 0;
			foreach ($this->_model as $model){
				$modelName = get_class($model);
				if (isset($def_data[$modelName])){
					try {
						$result = $model->update($def_data[$modelName], $model->getAdapter()->quoteInto($model->getPrimary()."=?", $this->actionId));
					} catch ( Exception $e ) {
						return array ('error' => $e->getMessage() );
					}
				}
			}
			return ($result === 0) ? true : $result;
		} else {
			return array ('valid' => $errors );
		}
	}

	protected function updateForm() {

		/*->setEnctype(Zend_Form::ENCTYPE_MULTIPART);*/

		/*$this->setAttrib('class', 'zend-form')
		 ->addAttribs(array(
		 'id'       => 'registration',
		 'onSubmit' => 'validate(this)'
		 ));*/

		$this->setAttrib ( 'accept-charset', 'UTF-8' );

		if($this->_decorate) $this->_decorateClass->decorateForm ( $this );
		/*$this->setDecorators(array(
		 'FormElements',
		 array('HtmlTag', array('tag' => 'div')),
		 'Form'
			));*/
	}

	public function getFieldByName($name) {
		if(key_exists($name,$this->fields)){
			return $this->fields[$name];
		}
		return false;
	}

	public function getFieldNameByColumnName($name) {
		foreach ( $this->fields as $f_name => $field ) {
			$c_name = $field->getName ();
			if ($c_name === $name)
			return $f_name;
		}
		return false;
	}

	public function getFieldByColumnName($name) {
		foreach ( $this->fields as $f_name => $field ) {
			$c_name = $field->getName ();
			if ($c_name === $name)
			return $field;
		}
		return false;
	}

	public function getModelById($id=0) {
		if (isset($this->_model[$id]) && ($this->_model[$id] instanceof ArOn_Db_Table || $this->_model[$id] instanceof ArOn_Cache_Type_Table)){
			return $this->_model[$id];
		}
		elseif (isset($this->modelName[$id]) && $this->modelName[$id]){
			return ArOn_Crud_Tools_Registry::singleton ( $this->modelName[$id] );
		}
		else return false;
	}

	public function getModelByName($name) {
		if (($id=array_search($name, $this->modelName)) !== false){
			return $this->getModelById($id);
		}
		else return false;
	}

	public function getModel($key=0) {
		if ($key){
			if ($model = $this->getModelByName($key)){
				return $model;
			}
			else{
				return $this->getModelById($key);
			}
		}
		else {
			return $this->getModelById(0);
		}
	}

	public function getModelName() {
		return $this->modelName;
	}

	public function getUrlParams() {
		return http_build_query($this->filterParams);
	}

	public function setDecorateClass(ArOn_Crud_Form_Decorator $decorator) {
		$this->_decorateClass = $decorator;
		return $this;
	}

	public function getDecoratorClass(){
		return $this->_decorateClass;
	}

	public function isDecorate(){
		return $this->_decorate;
	}

	public function setFilterPrefix($prefix){
		$this->filterPrefix = $prefix;
		return $this;
	}
	
	/**
	 * public function registerPlugin
	 *
	 *	@var ArOn_Crud_Form_Plugin_Abstract
	 */
	
	protected $_plugins = array();
	
	public function registerPlugin(ArOn_Crud_Form_Plugin_Abstract $plugin){

                if(!in_array($plugin, $this->_plugins)){

                        $this->_plugins[$plugin->toString()] = $plugin;
                } else {
                        throw new Exception('Function allready defined');
                }
                
                ksort($this->_plugins);
        }
        
    public function unregisterPlugin($plugin){
    			
    			if($plugin instanceof ArOn_Crud_Form_Plugin_Abstract){
    				$plugin = $plugin->toString();
    			}		
    	
                if(isset($this->_plugins[$plugin])){
                        unset($this->plugins[$plugin]);
                } else {
                        throw new Exception('No such function');
                }

    }

   /*public function __call($method, $args) {
              if(isset($this->_plugins[$method])){
                array_unshift(&$args, $this->data);
                array_unshift(&$args, $this);
                return $this->_plugins[$method]->run($args[0], $args[1], $args[2]);

              } else {
                throw new Exception('No such function: ' . $method);
              }
   }*/
	
	public function getClass(){
		return get_class ( $this );
	}
}