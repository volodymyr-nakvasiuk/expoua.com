<?PHP

Zend_Loader::loadClass("Admin_Feed", PATH_VIEWS);
Zend_Loader::loadClass("Zend_Json_Encoder");

class Admin_Feed_Json extends Admin_Feed {

	private $_data = array();

	protected function init() {
		Zend_Controller_Front::getInstance()->getResponse()->setHeader("Content-Type", "application/json; charset=utf-8", true);
	}

	public function assign($spec, $value = null) {
		if (!in_array($spec, $this->_skipElements)) {
			$this->_data[$spec] = $value;
		}
	}

	public function render($name) {
		echo Zend_Json_Encoder::encode($this->_data);
	}

}