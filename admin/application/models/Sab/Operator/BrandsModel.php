<?PHP
Zend_Loader::loadClass("Sab_Operator_ModelAbstract", PATH_MODELS);

class Sab_Operator_BrandsModel extends Sab_Operator_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_Brands';
	
	public function init() {
		parent::init();
		
		/*Убираем не нужные ограничительные параметры, поскольку нам нужно
		 * показывать все бренды с фильтром по орагнизатору
		 */
		unset($this->_DP_limit_params['countries_id']);
	}

}