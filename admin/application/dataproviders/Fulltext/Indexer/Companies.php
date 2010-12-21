<?PHP

Zend_Loader::loadClass("Fulltext_Indexer_Abstract", PATH_DATAPROVIDERS);

class Fulltext_Indexer_Companies extends Fulltext_Indexer_Abstract {

	protected $_db_table = 'ExpoPromoter_index.index_words_to_companies';

	//Базовое имя таблицы к которому потом добавляется язык
	protected $_db_table_base = 'ExpoPromoter_index.index_words_to_companies';

	public function addToIndex($id, $reindex = true) {
		if ($reindex) {
			$this->deleteFromIndex($id);
		}

		$dataObj = DataproviderAbstract::_DP("List_Joined_Ep_Companies");
		$data = $dataObj->getEntry($id, array('languages_id' => $this->_language['id']));

		$services_query = "SELECT csd.name, csd.short, csd.content
FROM ExpoPromoter_Opt.companies_services AS cs
JOIN ExpoPromoter_Opt.companies_services_data AS csd ON (cs.id=csd.id)
JOIN ExpoPromoter_Opt.companies_services_active AS csa ON (cs.id=csa.id)
WHERE csa.active=1 AND csd.languages_id=csa.languages_id AND csd.languages_id=:lang AND cs.companies_id=:comp";

		$services_stmt = self::$_db->query($services_query, array(':lang' => $this->_language['id'], ':comp' => $id));

		$services_data = array('names' => '', 'shorts' => '' ,'contents' => '');
		while ($tmp = $services_stmt->fetch()) {
			$services_data['names'] .= " " . $tmp['name'];
			$services_data['shorts'] .= " " . $tmp['short'];
			$services_data['contents'] .= " " . $tmp['content'];
		}

		//print_r($services_data);

		$res = $this->_putDataToIndex($id, array(
				array('data' => $data['name'], 'type' => 'plain', 'weight' => 10),
				array('data' => $services_data['names'], 'type' => 'plain', 'weight' => 8),
				array('data' => $data['description'], 'type' => 'html', 'weight' => 5),
				array('data' => $services_data['shorts'], 'type' => 'plain', 'weight' => 4),
				array('data' => $services_data['contents'], 'type' => 'html', 'weight' => 1)
			));

		return $res;
	}

	public function rebuildIndex() {

		$this->truncateIndex();

		$services_query = "SELECT csd.name, csd.short, csd.content
FROM ExpoPromoter_Opt.companies_services AS cs
JOIN ExpoPromoter_Opt.companies_services_data AS csd ON (cs.id=csd.id)
JOIN ExpoPromoter_Opt.companies_services_active AS csa ON (cs.id=csa.id)
WHERE csa.active=1 AND csd.languages_id=csa.languages_id AND csd.languages_id=:lang AND cs.companies_id=:comp";

		$select = self::$_db->select();

		$select->from("ExpoPromoter_Opt.companies", "id");
		$select->join("ExpoPromoter_Opt.companies_active", "companies_active.id = companies.id", array());
		$select->join("ExpoPromoter_Opt.companies_data", "companies.id = companies_data.id", array('name', 'description'));
		$select->where("companies_data.languages_id = ?", $this->_language['id']);
		$select->where("companies_active.languages_id = companies_data.languages_id");
		$select->where("companies_active.active = 1");

		$stmt = self::$_db->query($select);

		$res = 0;
		while ($el = $stmt->fetch()) {

			echo $el['id'] . " ";

			$services_stmt = self::$_db->query($services_query, array(':lang' => $this->_language['id'], ':comp' => $el['id']));

			$services_data = array('names' => '', 'shorts' => '' ,'contents' => '');
			while ($tmp = $services_stmt->fetch()) {
				$services_data['names'] .= " " . $tmp['name'];
				$services_data['shorts'] .= " " . $tmp['short'];
				$services_data['contents'] .= " " . $tmp['content'];
			}

			$res += $this->_putDataToIndex($el['id'], array(
				array('data' => $el['name'], 'type' => 'plain', 'weight' => 10),
				array('data' => $services_data['names'], 'type' => 'plain', 'weight' => 8),
				array('data' => $el['description'], 'type' => 'html', 'weight' => 5),
				array('data' => $services_data['shorts'], 'type' => 'plain', 'weight' => 4),
				array('data' => $services_data['contents'], 'type' => 'html', 'weight' => 1)
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

		$this->_db_table = $this->_db_table_base . "_" . $code;
	}
}