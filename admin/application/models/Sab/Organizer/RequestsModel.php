<?PHP

Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_RequestsModel extends Sab_Organizer_ModelAbstract {

	protected $_DP_name = 'List_Requests';

	public function getEntry($id) {
		$this->_DP_limit_params['parent'] = $this->getUserOrganizerId();
		$data = parent::getEntry($id);

		if (!empty($data)) {
			$data['details'] = $this->_DP_obj->getEntryData($id);

			$this->_DP_obj->updateEntry($id, array('viewed' => 1));
		}

		return $data;
	}

	public function getBrandsList() {
		return $this->_DP("List_Joined_Ep_Brands")->getList(null, null, $this->_DP_limit_params, array('name' => 'ASC'));
	}

}