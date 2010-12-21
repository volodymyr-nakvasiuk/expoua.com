<?PHP

Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_AclModel extends Sab_Organizer_ModelAbstract {

	protected $_DP_name = 'List_Operators';

	public function getEntry($id) {
		$res = parent::getEntry($id);

		$res['barads_list'] = $this->_DP_obj->getOrganizerBrands($id);

		$res['acl_list'] = $this->_DP_obj->getOrganizerResources($id);

		return $res;
	}

	public function getList($page = null, $sort = null, $search = null) {
		$this->_DP_obj->addColsToList(array('name_fio'));
		return parent::getList($page, $sort, $search);
	}

	public function insertEntry(Array $data, $insertAllLangs = false) {
		$data['type'] = 'organizer';

		$prefix = Zend_Auth::getInstance()->getIdentity();
		$data['login'] = $prefix . "_" . $data['login'];
		$data['user_languages_id'] = self::$_user_language_id;

		//Zend_Debug::dump($data);

		$res = parent::insertEntry($data, false);

		if (empty($data['super']) && $res == 1) {
			$new_org_id = $this->_DP_obj->getLastInsertId();
			$resources_ids = array(63, 123);
			if (isset($data['acl_events_drafts'])) {
				$resources_ids[] = 65; //drafts
				$resources_ids[] = 66; //brandsevents
				$resources_ids[] = 67; //files
				$resources_ids[] = 70; //logo
			}
			if (isset($data['acl_emails'])) {
				$resources_ids[] = 73;
			}
			if (isset($data['acl_news'])) {
				$resources_ids[] = 68;
			}
			if (isset($data['acl_requests'])) {
				$resources_ids[] = 89;
			}

			$this->_DP_obj->updateOrganizerResources($new_org_id, $resources_ids);

			if (!empty($data['brands'])) {
				$this->_DP_obj->updateOrganizerBrands($new_org_id, $data['brands']);
			}
		}

		return $res;
	}

	public function updateEntry($id, Array $data) {
		$this->_DP_limit_params['type'] = 'organizer';

		if (empty($data['super'])) {
			$resources_ids = array(63, 123);
			if (isset($data['acl_events_drafts'])) {
				$resources_ids[] = 65; //drafts
				$resources_ids[] = 66; //brandsevents
				$resources_ids[] = 67; //files
				$resources_ids[] = 70; //logo
			}
			if (isset($data['acl_emails'])) {
				$resources_ids[] = 73;
			}
			if (isset($data['acl_news'])) {
				$resources_ids[] = 68;
			}
			if (isset($data['acl_requests'])) {
				$resources_ids[] = 89;
			}

			$this->_DP_obj->updateOrganizerResources($id, $resources_ids);

			if (!empty($data['brands'])) {
				$this->_DP_obj->updateOrganizerBrands($id, $data['brands']);
			}

			$data['super'] = 0;
		} else {
			$this->_DP_obj->updateOrganizerResources($id, array());
			$this->_DP_obj->updateOrganizerBrands($id, array());
		}

		parent::updateEntry($id, $data);

		return 1;
	}

	public function getBrandsList() {
		$res = $this->_DP("List_Joined_Ep_Brands")->getList(null, null, $this->_DP_limit_params);

		return $res['data'];
	}

}