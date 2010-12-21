<?PHP

Zend_Loader::loadClass("Sab_Company_ModelAbstract", PATH_MODELS);

class Sab_Company_EventspartModel extends Sab_Company_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_Companies';

	public function getEntry($id) {

		$id = $this->getUserCompanyId();

		$res = parent::getEntry($id);

		$res['list_events'] = $this->_DP_obj->getEventsList($id);
		//$res['list_categories'] = $this->_DP_obj->getLinkedCategoriesList($id);

		return $res;
	}

	public function updateEntry($id, Array $data) {
		$id = $this->getUserCompanyId();

		//Подготавливаем связи с событиями
		if (isset($data['companies_to_events']) && is_array($data['companies_to_events'])) {
			//Zend_Debug::dump($data['companies_to_events']);
			$this->_DP_obj->updateEventsList($id, $data['companies_to_events']);
		}

		return 1;
	}

}