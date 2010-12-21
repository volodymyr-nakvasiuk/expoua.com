<?PHP
require_once(PATH_MODELS . "/Admin/ListModelAbstract.php");

class Admin_Ep_Pblbanners_StatModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Banners_PblStat';

	public function getEntry($id) {
		$res = array();

		$res['banner']  = $this->_DP('List_Banners_PblBanners')->getEntry($id);
		$res['general'] = $this->_DP_obj->getEntryGeneral($id);
		$res['modules'] = $this->_DP_obj->getEntryModules($id);
		$res['sites']   = $this->_DP_obj->getEntrySites($id);
		$res['clicks']  = $this->_DP_obj->getEntryClicks($id);

		return $res;
	}

	public function getList($page = null, $parent = -1, $sort = null, $search = null) {
		$res = parent::getList($page, $parent, $sort, $search);

		//Zend_Debug::dump($search);

		//Добавляем итог
		if (!empty($search)) {
			$search = $this->_prepareSearchConditions($search);
		} else {
			$search = array();
		}

		//Zend_Debug::dump($search);

		$res['total'] = $this->_DP_obj->getTotal($search);
		return $res;
	}

}