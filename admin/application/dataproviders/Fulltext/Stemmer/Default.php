<?PHP

Zend_Loader::loadClass("Fulltext_Stemmer_Abstract", PATH_DATAPROVIDERS);

/**
 * Стеммер по-умолчанию, который просто возвращает полученное слово без изменений
 *
 */
class Fulltext_Stemmer_Default extends Fulltext_Stemmer_Abstract {
	
	public function stem($word) {
		return $word;
	}
	
}