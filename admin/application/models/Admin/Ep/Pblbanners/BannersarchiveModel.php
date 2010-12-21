<?PHP
require_once(PATH_MODELS . "/Admin/ListModelAbstract.php");

class Admin_Ep_Pblbanners_BannersarchiveModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Banners_PblBanners';

	public function updateEntry($id, Array $data) {
		$this->_DP_limit_params = array();

		$res = parent::updateEntry($id, $data);

		if ($res) {
			//Обновляем материализованное представление
			$this->_DP_obj->updateBannerMView($id);
		}

		return $res;
	}


	public function getList($page = null, $parent = -1, $sort = null, $search = null) {
		$this->_DP_limit_params = array('deleted' => -1);

		return parent::getList($page, $parent, $sort, $search);
  }


	public function deleteEntry($id) {
		$res = parent::deleteEntry($id);

		if ($res) {
			//Обновляем материализованное представление
			$this->_DP_obj->updateBannerMView($id);
		}

		return $res;
	}

}