<?PHP
require_once(PATH_MODELS . "/Admin/ListModelAbstract.php");

class Admin_Ep_Banners_PlansModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Banners_Plans';

	public function getList($page = null, $parent = -1, $sort = null, $search = null) {

		if ($parent > 0) {
			$this->_DP_limit_params = array('companies_id' => $parent);
		}

		return parent::getList($page, $parent, $sort, $search);
	}

	public function getEntry($id) {
		$res = parent::getEntry($id);

		$res['selected_modules'] = $this->_DP_obj->getSelectedModules($id);
		$res['selected_categories'] = $this->_DP_obj->getSelectedCategories($id);
		$res['selected_publishers'] = $this->_DP_obj->getSelectedPublishers($id);
		$res['selected_banners'] = $this->_DP_obj->getSelectedBanners($id);

		return $res;
	}

	public function insertEntry(Array $data, $insertAllLangs = false) {
		$res = parent::insertEntry($data, $insertAllLangs);

		if ($res) {
			$id = $this->_DP_obj->getLastInsertId();
			$this->_updateSelectedModules_Cats($id, $data);
		}

		return $res;
	}

	public function updateEntry($id, Array $data) {

		if (empty($data['priority'])) {
			$data['priority'] = 65000;
		}

		parent::updateEntry($id, $data);

		$this->_updateSelectedModules_Cats($id, $data);

		return 1;
	}

	public function getCategoriesList() {
		$res = $this->_DP("List_Joined_Ep_BrandsCategories")
			->getList(null, null, array('languages_id' => 1), array('name' => 'ASC'));

		return $res['data'];
	}

	public function getModulesList() {
		$res = $this->_DP("List_Banners_Modules")->getList();

		return $res['data'];
	}

	public function getPublishersList() {
		$res = $this->_DP("List_Banners_Publishers")->getList();

		return $res['data'];
	}

	public function getPlacesList() {
		$res = $this->_DP("List_Banners_Places")->getList();

		return $res['data'];
	}

	private function _updateSelectedModules_Cats($id, Array $data) {
			if (!isset($data['modules_id']) || !is_array($data['modules_id'])) {
				$modules = array();
			} else {
				$modules = $data['modules_id'];
			}
			if (!isset($data['categories_id']) || !is_array($data['categories_id'])) {
				$cats = array();
			} else {
				$cats = $data['categories_id'];
			}
			if (!isset($data['publishers_id']) || !is_array($data['publishers_id'])) {
				$publishers = array();
			} else {
				$publishers = $data['publishers_id'];
			}

			$banners = array();
			if (isset($data['banners_id']) && is_array($data['banners_id'])) {
				foreach ($data['banners_id'] as $key => $el) {
					if (is_numeric($el) && $el>0) {
						$banners[$key] = intval($el);
					}
				}
			}

			$this->_DP_obj->updateSelectedModules($id, $modules);
			$this->_DP_obj->updateSelectedCategories($id, $cats);
			$this->_DP_obj->updateSelectedPublishers($id, $publishers);
			$this->_DP_obj->updateSelectedBanners($id, $banners);

			$this->_DP_obj->updateMView();
	}
}