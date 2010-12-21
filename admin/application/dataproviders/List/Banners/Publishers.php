<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_Banners_Publishers extends List_Abstract {

	protected $_allowed_cols = array('id', 'sites_id', 'name', 'url', 'description');

	protected $_db_table = "ExpoPromoter_banners.publishers";

	protected $_select_list_cols = array('id', 'name');

	protected $_sort_col = array('id' => 'DESC');

	public function getSelectedPlaces($id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_banners.publishers_to_places", array('places_id', 'places_id'));
		$select->where("publishers_id = ?", $id);

		return self::$_db->fetchPairs($select);
	}

	public function getSelectedLanguages($id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_banners.publishers_to_languages", array('languages_id', 'languages_id'));
		$select->where("publishers_id = ?", $id);

		return self::$_db->fetchPairs($select);
	}

	public function updateSelectedLanguages($id, Array $languages) {
		$where = self::$_db->quoteInto("publishers_id = ?", $id);
		self::$_db->delete("ExpoPromoter_banners.publishers_to_languages", $where);

		$result = 0;
		foreach ($languages as $el) {
			$row = array('publishers_id' => $id, 'languages_id' => intval($el));
			$result += self::$_db->insert("ExpoPromoter_banners.publishers_to_languages", $row);
		}

		return $result;
	}

	public function updateSelectedPlaces($id, Array $places) {
		$where = self::$_db->quoteInto("publishers_id = ?", $id);
		self::$_db->delete("ExpoPromoter_banners.publishers_to_places", $where);

		$result = 0;
		foreach ($places as $el) {
			$row = array('publishers_id' => $id, 'places_id' => intval($el));
			$result += self::$_db->insert("ExpoPromoter_banners.publishers_to_places", $row);
		}

		return $result;
	}

	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {

		if (array_key_exists('places_id', $params)) {
			$select_sq = self::$_db->select();
			$select_sq->from('ExpoPromoter_banners.publishers_to_places', 'publishers_id');
			$select_sq->where('places_id ?', $params['places_id']);

			$select->where('id IN ?', $select_sq);
		}

		parent::_SqlAddsWhere($select, $params);
	}
}