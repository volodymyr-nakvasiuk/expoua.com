<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_Buyers_Buyers extends List_Abstract {

	protected $_allowed_cols = array('id', 'active', 'show', 'events_id', 'money', 'max_money',
		'buyers_required', 'money_request', 'max_money_request', 'buyers_request',
		'geography', 'contact_name', 'contact_phone', 'contact_email');

	protected $_db_table = "ExpoPromoter_Opt.buyers";

	protected $_select_list_cols = array('id', 'active', 'events_id', 'money');

	protected $_sort_col = array('id' => 'DESC');

	/**
	 * Сохраняет транзакцию (начисление денег из админки) в таблице транзакций
	 * 
	 * @param $id
	 * @param $data
	 * @return int
	 */
	public function insertTransaction($id, Array $data) {
		$data_insert = array(
			'type' => 'deposit',
			'summ' => $data['summ'],
			'id_buyer' => $id
		);
		
		return self::$_db->insert("ExpoPromoter_Opt.transactions", $data_insert);
	}
	
	protected function _SqlAddsList(Zend_Db_Select &$select) {
		$select->join("ExpoPromoter_Opt.events", "events.id = buyers.events_id", array('brands_id'));
		$select->join("ExpoPromoter_Opt.brands_data", "brands_data.id = events.brands_id",
			array('brand_name' => 'name'));
			
		// Не пытайтесь повторить это дома!
		$select->from("", array("deposit" => new Zend_Db_Expr(
			"(SELECT SUM(summ) FROM ExpoPromoter_Opt.transactions WHERE id_buyer = buyers.id)")));
		
		$select->where("brands_data.languages_id = ?", Zend_Registry::get("language_id"));
	}
	
}