<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Acl_OrganizersModel extends Admin_ListModelAbstract {

	protected $_DP_name = "List_Operators";

	protected $_DP_limit_params = array('type' => 'organizer');
	
	public function updateEntry($id, Array $data) {
		
		//Если пользователь отмечен как супер-пользователь, снимаем у него все ограничения
		if (isset($data['super']) && $data['super'] == 1) {
			$this->_DP_obj->updateOrganizerResources($id, array());
			$this->_DP_obj->updateOrganizerBrands($id, array());
		}

		$entry = $this->_DP_obj->getEntry($id);
		$res = parent::updateEntry($id, $data);
		
		if ($res) {
			if (!empty($entry['organizers_id'])) {
				$entry_org = $this->_DP("List_Joined_Ep_Organizers")->getEntry($entry['organizers_id']);
				$entry['countries_id'] = $entry_org['countries_id'];
			}
			
			$entry['languages_id'] = $entry['user_languages_id'];
			
			if (isset($data['active']) && $data['active'] == 1 && $entry['active'] == 0) {
				$this->_DP("Email_PagesTemplate")->
					sendChangeStatusNotification($entry, 128, "info@expopromoter.com",
						"EGMS ExpoPromoter.com", "support@expopromogroup.com");
			} else if (isset($data['active']) && $data['active'] == 0 && $entry['active'] == 1) {
				$this->_DP("Email_PagesTemplate")->
					sendChangeStatusNotification($entry, 129, "info@expopromoter.com",
						"EGMS ExpoPromoter.com", "support@expopromogroup.com");
			}
		}
		
		return $res;
	}
}