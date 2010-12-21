<?PHP

Zend_Loader::loadClass("Admin_Ep_Ads_ModelAbstract", PATH_MODELS);

class Admin_Ep_Ads_ParticipantsModel extends Admin_Ep_Ads_ModelAbstract {

	protected $_DP_limit_params = array('type' => 'participant');

	protected $_DP_name = 'List_Joined_Ep_EventsAds';

}