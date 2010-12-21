<?PHP

Zend_Loader::loadClass("Sab_ListModelAbstract", PATH_MODELS);

class Sab_JsonfeedsModel extends Sab_ListModelAbstract {

	public $forceListResults = 23;

	public function getOrganizersList($page, $sort, $search) {
		$this->_DP_obj = $this->_DP("List_Joined_Ep_Organizers");
		return $this->getList($page, $sort, $search);
	}

	public function getOrganizer($id) {
		return $this->_DP("List_Joined_Ep_Organizers")->getEntry($id);
	}

	public function getEventsList($page, $sort, $search) {
		$this->_DP_obj = $this->_DP("List_Joined_Ep_BrandPlusEvent");
		return $this->getList($page, $sort, $search);
	}

	public function getBrandsList($page, $sort, $search) {

		$_org_sess = new Zend_Session_Namespace("Shelby_auth_organizer", true);

		if (isset($_org_sess->operator) && !empty($_org_sess->operator->list_brands)) {
			$this->_DP_limit_params['id'] = $_org_sess->operator->list_brands;
		}

		$this->_DP_obj = $this->_DP("List_Joined_Ep_Brands");
		return $this->getList($page, $sort, $search);
	}


	public function getBrandsCategoriesList() {
		$this->_DP_obj = $this->_DP("List_Joined_Ep_BrandsCategories");
		return $this->getList();
	}


	public function getBrandsCategory($id) {
		$this->_DP_limit_params = array_merge($this->_DP_limit_params, array('active' => 1));
		$this->_DP_obj = $this->_DP("List_Joined_Ep_BrandsCategories");

		if (false || strpos($id, ',')) {
			$ids = split(',', $id);
			$res = array();

			foreach ($ids as $id) {
				$res[$id] = $this->getEntry($id);
			}
			return $res;
		} else {
			return $this->getEntry($id);
		}
	}


	public function getBrandsSubCategoriesList($parent) {
		$params = array('active' => 1, 'languages_id' => self::$_user_language_id, 'parent_id' => $parent);
		return $this->_DP("List_Joined_Ep_BrandsSubCategories")->getList(null, null, $params, array('name' => 'ASC'));
	}


	public function getCitiesList($page, $sort, $search) {
		$this->_DP_limit_params = array_merge($this->_DP_limit_params,
			array(
				'active' => 1,
				'extended' => 0
			));

		$this->_DP_obj = $this->_DP("List_Joined_Ep_Cities");
		return $this->getList($page, $sort, $search);
	}

	public function getCity($id) {
		$this->_DP_limit_params = array_merge($this->_DP_limit_params, array('active' => 1));

		$this->_DP_obj = $this->_DP("List_Joined_Ep_Cities");
		return $this->getEntry($id);
	}

	public function getSocOrganizersList($page, $sort, $search)
	{
		$this->_DP_limit_params = array_merge($this->_DP_limit_params, array('active' => 1));
		$this->_DP_obj = $this->_DP("List_Joined_Ep_Socorgs");
		return $this->getList($page, $sort, $search);
	}

	public function getCountriesList($page, $sort, $search) {
		$this->_DP_limit_params = array_merge($this->_DP_limit_params, array('active' => 1));

		$this->_DP_obj = $this->_DP("List_Joined_Ep_Countries");
		return $this->getList($page, $sort, $search);
	}

	public function getCountry($id) {
		$this->_DP_limit_params = array_merge($this->_DP_limit_params, array('active' => 1));

		$this->_DP_obj = $this->_DP("List_Joined_Ep_Countries");
		return $this->getEntry($id);
	}

	public function getExpocentersList($page, $sort, $search) {
		$this->forceListResults = 999;
		$this->_DP_obj = $this->_DP("List_Joined_Ep_Expocenters");
		return $this->getList($page, $sort, $search);
	}

	public function getLastEventByBrand($id) {
		$events_list = $this->_DP("List_Joined_Ep_Events")->getList(1, null, array('brands_id' => $id), array('date_from' => 'DESC'));

		if (empty($events_list['data'])) {
			return false;
		}

		$event_entry = array_shift($events_list['data']);
		$event_id = $event_entry['id'];
		$event_entry = array();

		$langs_list = $this->getLanguagesList();
		foreach ($langs_list as $el) {
			$event_entry[$el['code']] = $this->_DP("List_Joined_Ep_Events")->getEntry($event_id, array('languages_id' => $el['id']));
		}

		return $event_entry;
	}

	public function getCompaniesServiceCatsList($page, $sort, $search) {
		$this->_DP_obj = $this->_DP("List_Joined_Ep_CompaniesServicesCats");
		return $this->getList($page, $sort, $search);
	}

	/**
	 * Функция возвращает 1 в случае если пользователь с таким логином существует, иначе вернет 0
	 *
	 * @param string $login
	 * @return int
	 */
	public function checkCompanyUser($login) {
		$res = $this->_DP("List_Users_Sites")->getList(null, null, array('login' => $login));
		if (empty($res['data'])) {
			return 0;
		} else {
			return 1;
		}
	}

	public function getCompaniesList($page, $sort, $search) {
		$this->_DP_obj = $this->_DP("List_Joined_Ep_Companies");
		return $this->getList($page, $sort, $search);
	}
}