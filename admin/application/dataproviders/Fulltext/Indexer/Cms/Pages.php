<?PHP

Zend_Loader::loadClass("Fulltext_Indexer_Abstract", PATH_DATAPROVIDERS);

class Fulltext_Indexer_Cms_Pages extends Fulltext_Indexer_Abstract {

	protected $_db_table = 'index_words_to_cms_pages';

	public function addToIndex($id, $reindex = true) {
		if ($reindex) {
			$this->deleteFromIndex($id);
		}

		$pageObj = DataproviderAbstract::_DP("List_Joined_Pages");
		$data = $pageObj->getEntry($id, array('languages_id' => $this->_language['id']));

		$res = $this->_putDataToIndex($id, array(
				array('data' => $data['name'], 'type' => 'plain', 'weight' => 1),
				array('data' => $data['content'], 'type' => 'html', 'weight' => 0)
			));

		return $res;
	}

	public function rebuildIndex() {

		$this->truncateIndex();

		$select = self::$_db->select();

		$select->from("cms_pages", "id");
		$select->join("cms_pages_data", "cms_pages.id = cms_pages_data.id", array('name', 'content'));
		$select->where("cms_pages_data.languages_id = ?", $this->_language['id']);
		$select->where("cms_pages.active = 1");

		$stmt = self::$_db->query($select);

		$res = 0;
		while ($el = $stmt->fetch()) {
			$res += $this->_putDataToIndex($el['id'], array(
				array('data' => $el['name'], 'type' => 'plain', 'weight' => 1),
				array('data' => $el['content'], 'type' => 'html', 'weight' => 0)
			));
		}

		return $res;
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