<?PHP

Zend_Loader::loadClass("Admin_ModelAbstract", PATH_MODELS);

class Admin_Ep_Banners_StatModel extends Admin_ModelAbstract {

	protected $_DP_obj = null;

	public function init() {
		parent::init();

		$this->_DP_obj = $this->_DP("List_Banners_Stat");
	}

	public function getEntry($id) {
		$res = array();

		$res['general'] =  $this->_DP_obj->getEntryGeneral($id);
		$res['modules'] = $this->_DP_obj->getEntryModules($id);
		$res['publishers'] = $this->_DP_obj->getEntryPublishers($id);
		$res['clicks'] = $this->_DP_obj->getEntryClicks($id);

		return $res;
	}

	public function getList($page = null, $parent = -1, $sort = null, $search = null) {

		$search_save = $search;
		$extraParams = array();

		if (is_array($sort)) {
			$sortOrder = $sort;
		} else {
			$sortOrder = array();
		}

		/*if ($parent != -1) {
			$extraParams['parent_id'] = $parent;
		}*/

		//Подготавливаем параметры поиска
		if (!is_null($search)) {
			$search = $this->_prepareSearchConditions($search);
			$extraParams = array_merge($search, $extraParams);
		}

		$results_num = $this->getConfigValue("GENERAL_ELEMENTS_PER_PAGE");

		return array_merge(array('search' => $search_save), $this->_DP_obj->getList($results_num, $page, $extraParams, $sortOrder));
	}

}