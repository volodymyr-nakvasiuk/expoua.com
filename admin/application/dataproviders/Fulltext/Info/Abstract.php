<?PHP
require_once(PATH_DATAPROVIDERS . "/Fulltext/Abstract.php");

/**
 * Абстрактный класс информатора о состоянии индекса.
 *
 */
abstract class Fulltext_Info_Abstract extends Fulltext_Abstract {
	
	/**
	 * Вовзвращает количество документов в индексе
	 * 
	 * @return int
	 */
	public function getDocumentsCount() {
		$select = self::$_db->select();
		
		$select->from($this->_db_table,
			array('cnt' => new Zend_Db_Expr("COUNT(DISTINCT parent_id)")));
		
		return self::$_db->fetchOne($select);
	}
	
	/**
	 * Возвращает количество слов в индексе
	 * Если в первом параметре передать true, то функция вернет количество
	 * уникальных слов
	 * 
	 * @return int
	 */
	public function getWordsCount($distinct = false) {
		$select = self::$_db->select();
		
		if ($distinct) {
			$select->from($this->_db_table,
				array('cnt' => new Zend_Db_Expr("COUNT(DISTINCT index_words_id)")));
		} else {
			$select->from($this->_db_table,
				array('cnt' => new Zend_Db_Expr("COUNT(index_words_id)")));
		}
		
		return self::$_db->fetchOne($select);
	}
	
	/**
	 * Возвращает список самых часто встречаемых слов (базовых словоформ) в
	 * индексе и их количество.
	 * В первом параметре передается количество слов, которые необходимо вернуть,
	 * во втором параметре передается страница для отображения
	 * 
	 * @param int $count
	 * @param int $page
	 * @return array
	 */
	public function getTopWords($count, $page) {
		$select_sq = self::$_db->select();
		
		$select_sq->from($this->_db_table,
			array('index_words_id', 'cnt' => new Zend_Db_Expr("COUNT(parent_id)")));
		$select_sq->group($this->_db_table . '.index_words_id');
		$select_sq->order("cnt DESC");
		$select_sq->limitPage($page, $count);
		
		$select = self::$_db->select();
		
		$select->from(array('sq' => $select_sq), array('cnt'));
		$select->join(Fulltext_Abstract::DB_TABLE_WORDS,
			Fulltext_Abstract::DB_TABLE_WORDS . ".id = sq.index_words_id",
			'name');
		
		return self::$_db->fetchAll($select);
	}
	
}