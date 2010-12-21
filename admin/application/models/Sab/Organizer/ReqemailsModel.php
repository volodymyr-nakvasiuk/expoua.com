<?PHP

Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_ReqemailsModel extends Sab_Organizer_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_Brands';

	public function getList($page = null, $sort = null, $search = null) {
		$this->_DP_obj->addColsToList(array('email_requests'));

		if (!empty($this->_DP_limit_params['brands_id'])) {
			$this->_DP_limit_params['id'] = $this->_DP_limit_params['brands_id'];
		}

    $result = parent::getList($page, array('name' => 'ASC'), $search);
    
    if (!empty($result)) {
      foreach ($result['data'] as $key => $el) {
        $events = $this->_DP('List_Joined_Ep_Events')->getList(
          1, 1, 
          array('brands_id' => $el['id']), 
          array('date_from' => 'DESC')
        );
        
        if (!empty($events) && !empty($events['data'])) {
          $event = array_shift($events['data']);
          $result['data'][$key]['events_id'] = $event['id'];
        }
      }
    } 

		return $result;
	}

	public function updateEntry($id, Array $data) {

		if (empty($data['email_requests'])) {
			return 0;
		}

		foreach ($data['email_requests'] as $key => $el) {
			$this->_DP_obj->updateEntry($key, array('email_requests' => $el), $this->_DP_limit_params);
		}

		return 1;
	}

}