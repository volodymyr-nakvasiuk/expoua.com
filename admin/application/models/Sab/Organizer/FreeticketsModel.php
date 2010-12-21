<?PHP

Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_FreeticketsModel extends Sab_Organizer_ModelAbstract {

	protected $_DP_name = 'List_EventsFreeTickets';
	
	public $_event_id = null;

	
	public function getEventsList($page = null, $sort = null, $search = null) {
	  $DP = $this->_DP_obj;
    $this->_DP_obj = $this->_DP('List_Joined_Ep_BrandPlusEvent');

		// $search[] = array('column' => 'date_to', 'value' => date("Y-m-d"), 'type' => '>=');

		$res = parent::getList($page, $sort, $search);
		
	  $this->_DP_obj = $DP;

		return $res;
	}


	public function getEventEntry($id) {
	  $DP = $this->_DP_obj;
    $this->_DP_obj = $this->_DP('List_Joined_Ep_Events');

		$res = parent::getEntry($id);
		
	  $this->_DP_obj = $DP;

		return $res;
	}


/*
  public function getUserEntry($id) {
	  $DP = $this->_DP_obj;
    $this->_DP_obj = $this->_DP('List_Users_Sites');

		$res = parent::getEntry($id);
		
		if (isset($res['functions'])) $res['functions'] = explode(',', $res['functions']);
		
	  $this->_DP_obj = $DP;

		return $res;
  }
*/

  public function getUserEntry($id) {
		$res = $this->_DP('List_Users_Sites')->getEntry($id);
		
		if (isset($res['functions'])) $res['functions'] = explode(',', $res['functions']);

		return $res;
  }


	public function getList($page = null, $sort = null, $search = null) {
	  
    $this->checkUserEventPermission($this->_event_id);
    
    $list = $this->_DP_obj->getList(1000, $page, array('events_id' => $this->_event_id), array('time_created' => "DESC"));
		return $list;
	}


	public function getEntry($id) {
		return $this->_DP_obj->getEntry($id, array('events_id' => $this->_event_id));
	}


}
