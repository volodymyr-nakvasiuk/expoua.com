<?PHP

require_once('Zend/Filter.php');
//Не справляется с русскими буквами правильно
//require_once('Zend/Filter/Alpha.php');
Zend_Loader::loadClass("Filter_Alnum", PATH_DATAPROVIDERS);
require_once('Zend/Filter/StripTags.php');
require_once('Zend/Filter/StringToLower.php');

abstract class Fulltext_Abstract extends DataProviderAbstract {

	//Таблица, содержащая стоп-слова
	const DB_TABLE_STOPWORDS = 'ExpoPromoter_index.index_stopwords';
	//Таблица, содержащая слова документов
	const DB_TABLE_WORDS = 'ExpoPromoter_index.index_words';
	//Минимальная длина индексируемого слова
	const WORD_MIN_LENGTH = 2;

	/**
	 * Таблица, содержащая отношения слов и документов + информация о позиции слова и его веса
	 * Для каждого типа индексируемых данных и языков своя
	 *
	 * @var string
	 */
	protected $_db_table = 'ExpoPromoter_index.index_words_to_';

	protected $_language = array();

	//Цепочка фильтров для обыкновенного текста
	protected $_filterChainPlain = null;

	//Экземпляр класса стеммера для текущего языка
	protected $_stemmerObj = null;

	/**
	 * Установка стеммера
	 *
	 * @param Fulltext_Stemmer_Abstract $stemmerObj
	 */
	public function setStemmer(Fulltext_Stemmer_Abstract &$stemmerObj) {
		$this->_stemmerObj = $stemmerObj;
	}

	/**
	 * Установка текущего языка
	 *
	 * @param string $code
	 * @param int $id
	 */
	public function setLanguage($code, $id) {
		$this->_language = array('code' => $code, 'id' => $id);
	}

	/**
	 * Возвращает массив стоп-слов для выбранного языка
	 * В течении сессии запрос к БД производится для каждого языка 1 раз, список кешируется
	 * Возвращет список по ссылке чтобы уменьшить расход памяти
	 *
	 * @return array
	 */
	protected function &_getStopwords() {
		static $cache = array();

		$lang_code = $this->_language['code'];

		if (isset($cache[$lang_code])) {
			return $cache[$lang_code];
		}

		$select = self::$_db->select();

		$select->from(Fulltext_Abstract::DB_TABLE_STOPWORDS, array('name'));
		$select->where("languages_code = ?", $lang_code);

		$cache[$lang_code] = self::$_db->fetchCol($select);

		return $cache[$lang_code];
	}
}