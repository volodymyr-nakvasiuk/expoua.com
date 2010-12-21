<?PHP

Zend_Loader::loadClass("Fulltext_Search_Abstract", PATH_DATAPROVIDERS);

class Fulltext_Search_Cms_Pages extends Fulltext_Search_Abstract {

	protected $_db_table = 'index_words_to_cms_pages';

	public function search($query, $results = 20, $page = 0) {
		$subquery = $this->_getSearchQuery($query);

		if (!$subquery instanceof Zend_Db_Select) {
			return false;
		}

		$subquery->limitPage($page, $results);

		//echo $subquery->__toString();

		$select = self::$_db->select();
		$select->from(array('sq' => $subquery), array('words', 'rank'));
		$select->join('cms_pages', "sq.parent_id = cms_pages.id", array('id'));
		$select->join('cms_pages_data', 'cms_pages.id = cms_pages_data.id', array('name', 'content'));
		$select->where('cms_pages_data.languages_id = ?', $this->_language['id']);
		$select->where("cms_pages.active = 1");

		return self::$_db->fetchAll($select);
	}

	/**
	 * Индексация производится для каждого языка в отдельную таблицу
	 *
	 * @param string $code
	 * @param int $id
	 */
	public function setLanguage($code, $id) {
		parent::setLanguage($code, $id);

		$this->_db_table .= "_" . $code;
	}

}