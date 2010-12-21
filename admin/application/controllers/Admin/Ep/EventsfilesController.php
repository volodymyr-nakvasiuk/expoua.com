<?PHP

Zend_Loader::loadClass("Admin_ListControllerAbstract", PATH_CONTROLLERS);

class Admin_Ep_EventsfilesController extends Admin_ListControllerAbstract {

	protected function _prepareSearch() {
		$res = parent::_prepareSearch();

		$event_id = $this->getRequest()->getUserParam("event_id", null);

		if (!is_null($event_id)) {
				$tmp[0] = array('column' => 'events_id', 'value' => $event_id, 'type' => "=");
			if (is_array($res)) {
				$res = array_merge($res, $tmp);
			} else {
				$res = $tmp;
			}
		}

		return $res;
	}

}