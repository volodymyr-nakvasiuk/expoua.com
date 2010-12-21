<?PHP
Zend_Loader::loadClass("Sab_Operator_ModelAbstract", PATH_MODELS);

class Sab_Operator_LogModel extends Sab_Operator_ModelAbstract {

	protected $_DP_name = 'List_LogOperators';

	public function init() {
		parent::init();
		
		unset($this->_DP_limit_params['type']);
	}
	
}