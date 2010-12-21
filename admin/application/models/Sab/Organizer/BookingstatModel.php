<?PHP

Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_BookingstatModel extends Sab_Organizer_ModelAbstract {

	//protected $_DP_name = 'List_Joined_Ep_BrandPlusEvent';

	public function getList($page = null, $sort = null, $search = null) {
		return $this->_DP("Statistics_Events")->
			getEventsComissionList(500, $page, $this->_DP_limit_params);
	}
}