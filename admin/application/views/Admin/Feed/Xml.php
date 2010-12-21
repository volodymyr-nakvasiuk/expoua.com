<?PHP

Zend_Loader::loadClass("Admin_Feed", PATH_VIEWS);

class Admin_Feed_Xml extends Admin_Feed {

	/**
	 * Экземпляр объекта
	 *
	 * @var DOMDocument
	 */
	private $_dom = array();

	/**
	 * Корневой элемент DOM-дерева
	 *
	 * @var DOMElement
	 */
	private $_dom_root;

	/**
	 * При инициализации посылаем заголовок верный.
	 * Кроме этого создаем новый DOM-объект
	 *
	 */
	protected function init() {
		Zend_Controller_Front::getInstance()->getResponse()->setHeader("Content-Type", "text/xml; charset=utf-8", true);

		$this->_dom = new DOMDocument("1.0", "UTF-8");
		//$this->_dom->formatOutput = true;

		$this->_dom_root = $this->_dom->createElement("feed");
		$this->_dom_root = $this->_dom->appendChild($this->_dom_root);
	}

	/**
	 * Переопределяем функцию передачи данных
	 * Заменяем ее функцией добавления ветви дерева DOM
	 *
	 * @param string $spec
	 * @param mixed $value
	 */
	public function assign($spec, $value = null) {
		if (!in_array($spec, $this->_skipElements)) {
			$this->_appendToDom($this->_dom_root, $spec, $value);
		}
	}

	/**
	 * Строим ветвь дерева DOM и добавляем ее к $domRoot
	 *
	 * @param DOMElement $domRoot
	 * @param string $name
	 * @param mixed $value
	 * @return DOMElement
	 */
	private function _appendToDom($domRoot, $name, $value) {
		if (is_array($value)) {
			$domElement = $this->_dom->createElement($name);

			foreach ($value as $key=>$el) {
				if (is_numeric($key)) {
					$key = "element";
				}
				$this->_appendToDom($domElement, $key, $el);
			}

			$domElement = $domRoot->appendChild($domElement);
		} else {
			//Кидается иногда ворнингами
			$domElement = @$this->_dom->createElement($name, (string)$value);
			$domElement = $domRoot->appendChild($domElement);
		}

		return $domElement;
	}

	/**
	 * Выводим сгенерированный XML
	 *
	 * @param string $name
	 */
	public function render($name) {
		echo $this->_dom->saveXML();
	}

}