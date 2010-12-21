<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_Banners_Banners extends List_Abstract {

	protected $_allowed_cols = array('id', 'advertisers_id', 'types_id', 'name', 'description', 'pline_events_id', 'text_content', 'file_alt', 'file_name');

	protected $_db_table = "ExpoPromoter_banners.banners";

	protected $_select_list_cols = array('id', 'name');

	protected $_sort_col = array('id' => 'DESC');

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$select->join("types", "types.id = banners.types_id",
			array('type_name' => 'name'), "ExpoPromoter_banners");
	}

	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {

		if (array_key_exists('companies_id', $params)) {
			$select_sq = self::$_db->select();
			$select_sq->from('ExpoPromoter_banners.companies', 'advertisers_id');
			$select_sq->where('id ?', $params['companies_id']);

			$select->where('advertisers_id = ?', $select_sq);
		}

		if (array_key_exists('places_id', $params)) {
			$select_sq = self::$_db->select();
			$select_sq->from('ExpoPromoter_banners.places_to_types', 'types_id');
			$select_sq->where('places_id ?', $params['places_id']);

			$select->where('types_id IN ?', $select_sq);
		}

		parent::_SqlAddsWhere($select, $params);
	}
}