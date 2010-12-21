<?PHP

Zend_Loader::loadClass("Fulltext_Indexer_Abstract", PATH_DATAPROVIDERS);

class Fulltext_Indexer_Companies_Services extends Fulltext_Indexer_Abstract {

	protected $_db_table = 'ExpoPromoter_index.index_words_to_comservices';

	//Базовое имя таблицы к которому потом добавляется язык
	protected $_db_table_base = 'ExpoPromoter_index.index_words_to_comservices';

	public function addToIndex($id, $reindex = true) {
		if ($reindex) {
			$this->deleteFromIndex($id);
		}

		$dataObj = DataproviderAbstract::_DP("List_Joined_Ep_CompaniesServices");
		$data = $dataObj->getEntry($id, array('languages_id' => $this->_language['id']));

		$res = $this->_putDataToIndex($id, array(
				array('data' => $data['name'], 'type' => 'plain', 'weight' => 8),
				array('data' => $data['short'], 'type' => 'plain', 'weight' => 4),
				array('data' => $data['content'], 'type' => 'html', 'weight' => 1)
			));

		return $res;
	}

	public function rebuildIndex() {

		$this->truncateIndex();

		$select = self::$_db->select();

		$select->from("ExpoPromoter_Opt.companies_services", array("id", 'companies_id'));
		$select->join("ExpoPromoter_Opt.companies", "companies.id = companies_services.companies_id", array());
		$select->join("ExpoPromoter_Opt.companies_services_data", "companies_services.id = companies_services_data.id", array('name', 'short', 'content'));
		$select->where("companies_services_data.languages_id = ?", $this->_language['id']);
		$select->where("companies_services.active = 1");
		$select->where("companies.active = 1");

		$stmt = self::$_db->query($select);

		$res = 0;
		while ($el = $stmt->fetch()) {

			echo $el['id'] . " ";

			$res += $this->_putDataToIndex($el['id'], array(
				array('data' => $el['name'], 'type' => 'plain', 'weight' => 8),
				array('data' => $el['short'], 'type' => 'plain', 'weight' => 4),
				array('data' => $el['content'], 'type' => 'html', 'weight' => 1)
			),
			array('companies_id' => $el['companies_id']));
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

		$this->_db_table = $this->_db_table_base . "_" . $code;
	}
}