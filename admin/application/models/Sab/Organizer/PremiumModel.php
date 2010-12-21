<?PHP

require_once(PATH_MODELS . "/Sab/Organizer/ModelAbstract.php");

class Sab_Organizer_PremiumModel extends Sab_Organizer_ModelAbstract {

	protected $_DP_name = null;

	public function getEventEntry($id) {
		return $this->_DP("List_Joined_Ep_Events")->getEntry($id);
	}

}