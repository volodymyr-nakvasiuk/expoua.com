<?PHP
require_once(PATH_MODELS . "/Admin/ListModelAbstract.php");

class Admin_Ep_Banners_CompaniesModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Banners_Companies';

	public function getList($page = null, $parent = -1, $sort = null, $search = null) {

		if ($parent > 0) {
			$this->_DP_limit_params = array('advertisers_id' => $parent);
		}

		return parent::getList($page, $parent, $sort, $search);
	}
}