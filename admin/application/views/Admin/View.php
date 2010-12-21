<?PHP

class Admin_View extends ViewAbstract {

	protected function init() {
		$this->_smarty->compile_id = "Backend";
		$this->_template = "core.tpl";
	}

	public function render($name) {
		$this->_smarty->display($this->_template);
	}

}