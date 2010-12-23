<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_Banners_Plans extends List_Abstract {

	protected $_allowed_cols = array('id', 'companies_id', 'places_id', 'name', 'url', 'date_from', 'date_to', 'priority');

	protected $_db_table = "ExpoPromoter_banners.plans";

	protected $_select_list_cols = array('id', 'name', 'date_from', 'date_to');

	protected $_prepare_cols = array(
				'date_from' => array('date', null),
				'date_to' => array('date', null)
	);

	protected $_sort_col = array('id' => 'DESC');

	public function getSelectedModules($id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_banners.plans_to_modules", array('modules_id', 'modules_id'));
		$select->where("plans_id = ?", $id);

		return self::$_db->fetchPairs($select);
	}

	public function getSelectedCategories($id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_banners.plans_to_categories", array('categories_id', 'categories_id'));
		$select->where("plans_id = ?", $id);

		return self::$_db->fetchPairs($select);
	}

	public function getSelectedPublishers($id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_banners.plans_to_publishers", array('publishers_id', 'publishers_id'));
		$select->where("plans_id = ?", $id);

		return self::$_db->fetchPairs($select);
	}

	public function getSelectedBanners($id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_banners.plans_to_banners", array('langs_id', 'banners_id'));
		$select->where("plans_id = ?", $id);

		return self::$_db->fetchPairs($select);
	}

	public function updateSelectedModules($id, Array $modules) {
		$where = self::$_db->quoteInto("plans_id = ?", $id);
		self::$_db->delete("ExpoPromoter_banners.plans_to_modules", $where);

		$result = 0;
		foreach ($modules as $el) {
			$row = array('plans_id' => $id, 'modules_id' => intval($el));
			$result += self::$_db->insert("ExpoPromoter_banners.plans_to_modules", $row);
		}

		return $result;
	}

	public function updateSelectedCategories($id, Array $cats) {
		$where = self::$_db->quoteInto("plans_id = ?", $id);
		self::$_db->delete("ExpoPromoter_banners.plans_to_categories", $where);

		$result = 0;
		foreach ($cats as $el) {
			$row = array('plans_id' => $id, 'categories_id' => intval($el));
			$result += self::$_db->insert("ExpoPromoter_banners.plans_to_categories", $row);
		}

		return $result;
	}

	public function updateSelectedPublishers($id, Array $publishers) {
		$where = self::$_db->quoteInto("plans_id = ?", $id);
		self::$_db->delete("ExpoPromoter_banners.plans_to_publishers", $where);

		$result = 0;
		foreach ($publishers as $el) {
			$row = array('plans_id' => $id, 'publishers_id' => intval($el));
			$result += self::$_db->insert("ExpoPromoter_banners.plans_to_publishers", $row);
		}

		return $result;
	}

	public function updateSelectedBanners($id, Array $banners) {
		$where = self::$_db->quoteInto("plans_id = ?", $id);
		self::$_db->delete("ExpoPromoter_banners.plans_to_banners", $where);

		$result = 0;
		foreach ($banners as $key => $el) {
			$row = array('plans_id' => $id, 'banners_id' => intval($el), 'langs_id' => intval($key));
			$result += self::$_db->insert("ExpoPromoter_banners.plans_to_banners", $row);
		}

		return $result;
	}

	/**
	 * Обновляем материализованное представление баннеров
	 *
	 */
	public function updateMView() { return;
		$query = "TRUNCATE ExpoPromoter_banners.mview_banners";

		self::$_db->query($query);

		$query = "INSERT INTO ExpoPromoter_banners.mview_banners (`id`, `languages_id`, `places_id`, `modules_id`, `categories_id`, `publishers_id`, `plans_id`,`priority`)
SELECT b.id, l.id AS lang_id, p.places_id, p2m.modules_id, p2c.categories_id, p2p.publishers_id, p.id, p.priority
FROM ExpoPromoter_banners.banners AS b
JOIN ExpoPromoter_banners.plans_to_banners AS p2b ON (p2b.banners_id=b.id)
JOIN ExpoPromoter_banners.plans AS p ON (p2b.plans_id=p.id)

LEFT JOIN ExpoPromoter_banners.plans_to_categories AS p2c ON (p2c.plans_id=p.id)
LEFT JOIN ExpoPromoter_banners.plans_to_modules AS p2m ON (p2m.plans_id =p.id)
LEFT JOIN ExpoPromoter_banners.plans_to_publishers AS p2p ON (p2p.plans_id =p.id)

JOIN ExpoPromoter_Opt.languages AS l ON (p2b.langs_id = l.id)
WHERE l.active=1 AND (p.date_from IS NULL OR p.date_from<=CURDATE()) AND (p.date_to IS NULL OR p.date_to>=CURDATE())";

		self::$_db->query($query);
	}

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$this->_SqlAddsEntry($select);
	}

	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		$select->join("companies",
			"companies.id = plans.companies_id",
			array('company_name' => 'name'), 'ExpoPromoter_banners');
	}

}