<?PHP

Zend_Loader::loadClass("Fulltext_Abstract", PATH_DATAPROVIDERS);

abstract class Fulltext_Search_Abstract extends Fulltext_Abstract {

	public function __construct() {

		//Обязательно нужно указать кодировку, иначе фигня на выходе получается
		$lower = new Zend_Filter_StringToLower();
		$lower->setEncoding("UTF-8");

		//Инициализация цепочки фильтров для plain-текста
		$this->_filterChainPlain = new Zend_Filter();
		$this->_filterChainPlain->addFilter(new Filter_Alnum(true))
														->addFilter($lower);
	}

	/**
	 * Функция поиска.
	 * Первым параметром передается строка запроса пользователя
	 * Вторым и третим количество результатов и страница соответственно
	 * Возвращает массив, содержащий результаты поиска
	 *
	 * @param string $query
	 * @param int $results
	 * @param int $page
	 * @return array
	 */
	abstract public function search($query, $results = 20, $page = 0);

	/**
	 * Подготавливает поисковый запрос и возвращает его объект
	 * Если запрос смысла не имеет, возвращает false
	 *
	 * @param string $user_query
	 * @return Zend_Db_Select
	 */
	protected function _getSearchQuery($user_query) {

		$words_array = $this->_prepareSearchQuery($user_query);

		if (empty($words_array)) {
			return false;
		}

		//(COUNT(DISTINCT w.id) + COUNT(w.id) + STDDEV_POP(idx.position) + SUM(idx.weight)) AS rank
		$select = self::$_db->select();
		$select->from(array('idx' => $this->_db_table), array(
			'parent_id',
			'words' => new Zend_Db_Expr('GROUP_CONCAT(DISTINCT w.name)'),
			/*'words_distinct_cnt' => new Zend_Db_Expr('COUNT(DISTINCT w.id)'),
			'words_cnt' => new Zend_Db_Expr('COUNT(w.id)'),
			'deviation' => new Zend_Db_Expr('STDDEV_POP(idx.position)'),
			'weight_sum' => new Zend_Db_Expr('SUM(idx.weight)'),*/
			'rank' => new Zend_Db_Expr('(COUNT(DISTINCT w.id)*(SUM(idx.weight)+1) + (COUNT(w.id))/STDDEV_POP(idx.position))')));

		$select->join(array('w' => Fulltext_Abstract::DB_TABLE_WORDS), "w.id = idx.index_words_id", array());
		$select->where("w.languages_id = ?", $this->_language['id']);
		$select->where("w.name IN (?)", $words_array);
		$select->group('idx.parent_id');
		$select->order('rank DESC');

		return $select;
	}

	/**
	 * Подготавливает запрос.
	 * Удаляет из него все все незначащие символы, стоп-слова и приводит их к нормальной форме
	 * На выходе массив слов для поиска
	 *
	 * @param string $user_query
	 * @return array
	 */
	private function _prepareSearchQuery($user_query) {
		$user_query = $this->_filterChainPlain->filter($user_query);

		$words_array = explode(" ", $user_query);
		//Удаляем стоп-слова
		$words_array = array_diff($words_array, $this->_getStopwords());

		foreach ($words_array as $key => &$word) {
			if (empty($word) || mb_strlen($word, "UTF-8") <= Fulltext_Abstract::WORD_MIN_LENGTH || is_numeric($word)) {
				unset($words_array[$key]);
				continue;
			}

			$word = $this->_stemmerObj->stem($word);
		}

		return array_unique($words_array);
	}

}