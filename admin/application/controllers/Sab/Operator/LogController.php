<?PHP
Zend_Loader::loadClass("Sab_Operator_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Operator_LogController extends Sab_Operator_ControllerAbstract {
	
	public function init() {
		parent::init();
		
		//Отключаем модуль, потому что создавал больше проблем, чем пользы
		$this->_helper->redirector->goto('list', 'sab_operator_drafts', null,
					array('language' => Zend_Registry::get("language_code")));
	}
	
	public function editAction() {}
	public function addAction() {}
	public function updateAction() {}
	public function insertAction() {}
	public function deleteAction() {}
}