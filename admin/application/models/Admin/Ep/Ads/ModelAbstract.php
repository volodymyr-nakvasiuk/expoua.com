<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

abstract class Admin_Ep_Ads_ModelAbstract extends Admin_ListModelAbstract {

	/**
	 * Переопределеям, исключая ограничивающие параметры чтобы можно было изменять тип объявления
	 *
	 * @param int $id
	 * @param array $data
	 * @return int
	 */
	public function updateEntry($id, Array $data) {
		$this->_DP_limit_params = array();

		$this->_checkData($data);

		return parent::updateEntry($id, $data);
	}

	public function insertEntry(Array $data, $insertAllLangs = false) {

		$this->_checkData($data);

		$data['date_pay'] = new Zend_Db_Expr("NOW()");

		return parent::insertEntry($data, $insertAllLangs);
	}

	private function _checkData(&$data) {
		if (empty($data['service_companies_id']) || intval($data['service_companies_id']) == 0) {
			$data['service_companies_id'] = null;
		}

		if (empty($data['events_participants_id']) || intval($data['events_participants_id']) == 0) {
			$data['events_participants_id'] = null;
		}
	}

}