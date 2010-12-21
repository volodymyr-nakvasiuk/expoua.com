<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_EventsfilesController extends Sab_Organizer_ControllerAbstract {

	protected function _prepareSearch() {
		$res = parent::_prepareSearch();

		$event_id = $this->getRequest()->getUserParam("event_id", 0);

		$tmp[0] = array('column' => 'events_id', 'value' => $event_id, 'type' => "=");

		if (is_array($res)) {
			$res = array_merge($res, $tmp);
		} else {
			$res = $tmp;
		}

		return $res;
	}

	protected function _checkAuth() {
		parent::_checkAuth();
		$this->_model->checkUserEventPermission($this->getRequest()->getUserParam("event_id", 0));
	}

}