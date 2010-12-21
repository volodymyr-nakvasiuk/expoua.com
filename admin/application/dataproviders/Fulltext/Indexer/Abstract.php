<?PHP
require_once('Zend/Filter/StripTags.php');

Zend_Loader::loadClass("Fulltext_Abstract", PATH_DATAPROVIDERS);

/**
 * Абстрактный класс индексатора.
 *
 */
abstract class Fulltext_Indexer_Abstract extends Fulltext_Abstract {

	//Цепочка фильтров для HTML-текста
	private $_filterChainHTML = null;

	public function __construct() {

		//Обязательно нужно указать кодировку, иначе фигня на выходе получается
		$lower = new Zend_Filter_StringToLower();
		$lower->setEncoding("UTF-8");

		//Инициализация цепочки фильтров для plain-текста
		$this->_filterChainPlain = new Zend_Filter();
		$this->_filterChainPlain->addFilter(new Filter_Alnum(true))
														->addFilter($lower);

		//Инициализация цепочки фильтров для HTML-текста
		$this->_filterChainHTML = new Zend_Filter();
		$this->_filterChainHTML->addFilter(new Zend_Filter_StripTags())
														->addFilter(new Filter_Alnum(true))
														->addFilter($lower);
	}

	/**
	 * Абстрактная функция, определяюща интерфейс добавления страницы в индекс.
	 * В качестве первого параметра передается идентификатор документа
	 * Второй необязательный параметр указывает на необходимость удаления старых записей
	 * при добавлении новой. Он используется при переиндексации ранее уже проиндексированного
	 * документа
	 * Функция возвращает количестов слов, добавленных в индекс
	 *
	 * @param int $id
	 * @param boolean $reindex
	 * @return int
	 */
	abstract public function addToIndex($id, $reindex = true);

	/**
	 * Полная перестройка индекса, либо создание нового с нуля
	 *
	 */
	abstract public function rebuildIndex();

	/**
	 * Удаляет запись из индекса
	 * Возвращает количество удаленных связей со словами
	 *
	 * @param int $id
	 * @return int
	 */
	public function deleteFromIndex($id) {
		$where = self::$_db->quoteInto("parent_id = ?", $id);

		return self::$_db->delete($this->_db_table, $where);
	}

	/**
	 * Полностью очищает индекс
	 *
	 */
	public function truncateIndex() {
		self::$_db->query("TRUNCATE " . $this->_db_table);
	}

	/**
	 * Помещает слова в индекс.
	 * Первым параметром передается идентификатор индексируемого документа
	 * В качестве второго параметра принимает многомерный массив, содержащий:
	 * data - данные для индексации
	 * type - тип данных
	 * weight - вес
	 * Третим опциональным параметром можно передать массив дополнительных столбцов для вставки
	 * эти значения являются константами. Их полезно использовать для индексации связанных данных
	 *
	 * Порядок имеет значение. В первую очеред необходимо добавлять самые важные данные,
	 * тогда позиция в индексе у них будет самая высокая
	 * Возвращает количество добавленных слов в индекс
	 *
	 * @param array $data_array
	 * @return int
	 */
	protected function _putDataToIndex($id, Array $data_array, Array $extra_rows = array()) {
		$pos = 0;
		$data = '';

		$query = "INSERT INTO " . $this->_db_table . " (index_words_id, parent_id, position, weight";
		foreach ($extra_rows as $key => $el) {
			$query .= ", " . $key;
		}
		$query .= ") VALUES ";

		foreach ($data_array as $data_element) {
			switch ($data_element['type']) {
				case "html":
					$data = $this->_prepareHTMLText($data_element['data']);
					break;
				default:
					$data = $this->_preparePlainText($data_element['data']);
			}

			$words_array = explode(" ", $data);
			//Удаляем стоп-слова
			$words_array = array_diff($words_array, $this->_getStopwords());

			foreach ($words_array as $word) {
				//Пропускаем пустые слова, слова содержащием мало букв и состоящие только из цифр
				if (empty($word) || mb_strlen($word, "UTF-8") <= Fulltext_Abstract::WORD_MIN_LENGTH || is_numeric($word)) {
					continue;
				}
				//echo $word . " " . $this->_stemmerObj->stem($word) . "\n";
				//echo $word . "\n";
				$word_id = $this->_getWordId($this->_stemmerObj->stem($word));
				//$query .= "(" . $word_id . ", " . $id . ", " . $pos . ", " . $data_element['weight'] . "),";

				$insertArray = array_merge(array($word_id, $id, $pos, $data_element['weight']), $extra_rows);

				$query .= self::$_db->quoteInto("( ? ),", $insertArray);

				$pos++;
			}
		}

		$query = rtrim($query, ",");

		//echo $query;

		if ($pos>0) {
			self::$_db->query($query);
		}

		return $pos;
	}

	/**
	 * Функция для подготовки текста к индексации в формате HTML
	 * Удаляет все теги, оставляет только текст
	 *
	 * @param string $text
	 * @return string
	 */
	protected function _prepareHTMLText($text) {
		//Вставляем поле каждого тега пробел чтобы исключить ситуации склеивания слов
		//Также делаем другие полезные замены
		$text = str_replace(
			array(">", "&nbsp;", "&amp;", "\r\n"),
			array("> ", " ", " ", " "), $text
		);

		return $this->_filterChainHTML->filter($text);
	}

	/**
	 * Функция подготовки обычного текста к индексации, оставляет только текст
	 *
	 * @param string $text
	 * @return string
	 */
	protected function _preparePlainText($text) {
		$text = str_replace("\r\n", " ", $text);
		return $this->_filterChainPlain->filter($text);
	}

	/**
	 * Возвращает идентификатор слова в БД
	 *
	 * @param string $word
	 * @return int
	 */
	protected function _getWordId($word) {
		static $cache = array();
		static $hits = 0;

		if (array_key_exists($word, $cache)) {
			$hits++;
			return $cache[$word];
		}

	$stmt = self::$_db->query("SELECT id FROM " . Fulltext_Abstract::DB_TABLE_WORDS . " WHERE languages_id=? AND name=?", array($this->_language['id'], $word));

	if ($id = $stmt->fetchColumn()) {
	} else {
		self::$_db->insert(Fulltext_Abstract::DB_TABLE_WORDS, array('languages_id' => $this->_language['id'], 'name' => $word));

		$id = self::$_db->lastInsertId();
	}

	$cache[$word] = $id;

	//Если кеш слишком разрастается, обнунялем его, поскольку поиск по большому массиву неэффективен
	if (sizeof($cache) > 3000) {
		//echo "\n--------------------- Dropping words cache --------------------- Hits = " . $hits . "\n";
		$hits = 0;
		$cache = array();
	}

	return $id;
}

}