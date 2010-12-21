<?PHP
Zend_Loader::loadClass("Admin_Feed", PATH_VIEWS);

/**
 * Пустой класс, используется если вывод никакой не требуется вовсе
 *
 */
class Admin_Feed_Null extends Admin_Feed {

	public function init() {
	}

	public function assign($spec, $value = null) {
	}

	public function render($name) {
	}

}