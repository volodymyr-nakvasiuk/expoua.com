<?PHP
Zend_Loader::loadClass("Frontend_View", PATH_VIEWS);

class Frontend_View_Console extends Frontend_View {

	public function __construct() {
		$this->_requestParams['language'] = 'ru';
		$this->_initTemplateEngine();
		$this->init();
		$this->_attachHelpers();
	}

	public function fetch($template = "", $lang_id = null) {
	  $ttt = date("Ymdhis");
	  
		$this->_smarty->compile_id = "Console_Cache_" . Zend_Registry::get("language_id");

    // echo "[{$this->_smarty->compile_id}]";

		if (empty($template)) {
			return $this->_smarty->fetch($this->_template);
		} else {
			return $this->_smarty->fetch($template);
		}
	}

	protected function _attachHelpers() {
		//Различные функции
		Zend_Loader::loadClass("ViewHelpers_Mixed", PATH_VIEWS);
		$mixedObj = new ViewHelpers_Mixed();
		$this->_smarty->assign_by_ref("HMixed", $mixedObj);
	}
}