<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_Banners_Places extends List_Abstract {

	protected $_allowed_cols = array('id', 'name', 'code');

	protected $_db_table = "ExpoPromoter_banners.places";

	protected $_select_list_cols = array('id', 'name');


	public function getSelectedTypes($id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_banners.places_to_types", array('types_id', 'types_id'));
		$select->where("places_id = ?", $id);

		return self::$_db->fetchPairs($select);
	}

	public function updateSelectedTypes($id, Array $types) {
		$where = self::$_db->quoteInto("places_id = ?", $id);
		self::$_db->delete("ExpoPromoter_banners.places_to_types", $where);

		$result = 0;
		foreach ($types as $el) {
			$row = array('places_id' => $id, 'types_id' => intval($el));
			$result += self::$_db->insert("ExpoPromoter_banners.places_to_types", $row);
		}

		return $result;
	}

}