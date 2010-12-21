<?PHP

Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_BookingwidgetModel extends Sab_Organizer_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_Events';

	public function getLocation($cities_id) {
		$langs = $this->getLanguagesList();
		$res = array();
		foreach ($langs as $lang) {
			$res[$lang['code']] = $this->_DP("List_Joined_Ep_Cities")->
				getGeoNameInfo($cities_id, $lang['id']);
		}
		return $res;
	}
	
	public function getAffiliateByEventsId($events_id) {
		return DataproviderAbstract::getDatabaseObjectInstance()->
			fetchRow("SELECT a.* FROM booking.affiliates AS a
JOIN booking.affiliate_types AS at ON (a.id_type = at.id)
WHERE at.type = 'org' AND events_id = ?", array($events_id));
	}
	
	public function getList($page = null, $sort = null, $search = null) {
		return $this->_DP("List_Joined_Ep_BrandPlusEvent")->
			getList(null, null, $this->_DP_limit_params, array('name' => 'ASC'));
	}

    public function getUserInfo() {
        return $this->_user_session->operator;
    }
}