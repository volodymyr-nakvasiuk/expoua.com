<?PHP

Zend_Loader::loadClass("Fulltext_Indexer_Abstract", PATH_DATAPROVIDERS);

class Fulltext_Indexer_Companies_Services_Tags extends Fulltext_Indexer_Abstract {

	protected $_db_table = 'ExpoPromoter_index.tags_to_comservices';

	//Базовое имя таблицы к которому потом добавляется язык
	protected $_db_table_base = 'ExpoPromoter_index.tags_to_comservices';

	public function addToIndex($id, $reindex = true) {
		//Не решил пока как индексировать по одному
		return;
		
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

		$select->from("ExpoPromoter_Opt.companies_services", array("id"));
		$select->join("ExpoPromoter_Opt.companies_services_active", "companies_services.id = companies_services_active.id", array());
		$select->join("ExpoPromoter_Opt.companies_services_cats", "companies_services_cats.id = companies_services.companies_services_cats_id", array());
		$select->join("ExpoPromoter_Opt.companies_services_cats_data", "companies_services_cats.id = companies_services_cats_data.id", array('name'));
		$select->where("companies_services_cats_data.languages_id = companies_services_active.languages_id AND companies_services_cats_data.languages_id = ?", $this->_language['id']);
		$select->where("companies_services_cats.active = 1 AND companies_services_active.active = 1");

		//echo $select->__toString();
		
		$stmt = self::$_db->query($select);

		$res = 0;
		while ($el = $stmt->fetch()) {

			//echo $el['id'] . " ";

			$res += $this->_putDataToIndex($el['id'], array(
				array('data' => $el['name'])
			));
		}

		return $res;
	}
	
	/**
	 * Обновляет информацию о весе каждого тега
	 * Производится полная перестройка таблицы с данными
	 */
	public function rebuildTagsWeight() {
		
		$db_table_tags = 'ExpoPromoter_index.tags_to_comservices_' . $this->_language['code'];
		$db_table_tags_weight = 'ExpoPromoter_index.tags_comservices_weight_' . $this->_language['code'];
		
		self::$_db->query("TRUNCATE " . $db_table_tags_weight);
		
		//Для всех категорий вцелом
		self::$_db->query("INSERT INTO " . $db_table_tags_weight . " (index_words_id, cnt)
			SELECT index_words_id, COUNT(parent_id) AS cnt FROM " . $db_table_tags . "
			GROUP BY index_words_id");
		
		//Для каждой категории в отдельности
		self::$_db->query("INSERT INTO " . $db_table_tags_weight . " (index_words_id, brands_categories_id, cnt)
			SELECT t2c.index_words_id, c2bc.brands_categories_id, COUNT(t2c.parent_id) AS cnt
			FROM " . $db_table_tags . " AS t2c
			JOIN ExpoPromoter_Opt.companies_services AS cs ON (cs.id=t2c.parent_id)
			JOIN ExpoPromoter_Opt.companies_to_brands_categories AS c2bc ON (cs.companies_id=c2bc.companies_id)
			GROUP BY t2c.index_words_id, c2bc.brands_categories_id");
		
		$select = self::$_db->select();
		$select->from('ExpoPromoter_Opt.brands_categories', 'id');
		$select->where('active = 1');
		
		$stmt = self::$_db->query($select);
		
		while ($row = $stmt->fetchColumn()) {
			self::$_db->query("SELECT MAX(cnt) INTO @maxx FROM " . $db_table_tags_weight . " WHERE brands_categories_id = ?", array($row));
			self::$_db->query("UPDATE " . $db_table_tags_weight . " SET weight=((cnt/@maxx)*100) WHERE brands_categories_id = ?", array($row));
		}
		
		self::$_db->query("SELECT MAX(cnt) INTO @maxx FROM " . $db_table_tags_weight . " WHERE brands_categories_id = 0");
		self::$_db->query("UPDATE " . $db_table_tags_weight . " SET weight=((cnt/@maxx)*100) WHERE brands_categories_id = 0");
	}

	/**
	 * Переопределяем функцию помещения данных в индекс поскольку часть функциональности,
	 * заложенной в абстрактном классе не нужна
	 * 
	 * @param int $id
	 * @param array $data_array
	 * @param array $extra_rows
	 * @return int
	 */
	protected function _putDataToIndex($id, Array $data_array, Array $extra_rows = array()) {
		$pos = 0;
		$data = '';

		$query = "INSERT INTO " . $this->_db_table . " (index_words_id, parent_id) VALUES ";

		foreach ($data_array as $data_element) {
			
			$data = $this->_preparePlainText($data_element['data']);

			$words_array = explode(" ", $data);
			//Удаляем стоп-слова
			$words_array = array_diff($words_array, $this->_getStopwords());

			foreach ($words_array as $word) {
				//Пропускаем пустые слова, слова содержащием мало букв и состоящие только из цифр
				if (empty($word) || mb_strlen($word, "UTF-8") <= Fulltext_Abstract::WORD_MIN_LENGTH || is_numeric($word)) {
					continue;
				}
				
				$word_id = $this->_getWordId($word);

				$insertArray = array($word_id, $id);

				$query .= self::$_db->quoteInto("( ? ),", $insertArray);

				$pos++;
			}
		}

		$query = rtrim($query, ",");

		if ($pos>0) {
			self::$_db->query($query);
		}

		return $pos;
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