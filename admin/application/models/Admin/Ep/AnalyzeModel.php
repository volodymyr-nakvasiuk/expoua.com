<?PHP

Zend_Loader::loadClass("Admin_ModelAbstract", PATH_MODELS);

class Admin_Ep_AnalyzeModel extends Admin_ModelAbstract {

	protected $_DP_obj = null;

	public function init() {
		parent::init();

		$this->_DP_obj = $this->_DP("Database_EPAnalyze");
	}

	public function getBrandsWithoutFutureEvents($year, $country, $organizer) {
		return $this->_DP_obj->getBrandsWithoutFutureEvents($year, $country, $organizer);
	}

	public function getBrandsWithoutEvents() {
		return $this->_DP_obj->getBrandsWithoutEvents();
	}

	public function getOrgsWithoutBrands() {
		return $this->_DP_obj->getOrgsWithoutBrands();
	}

	public function getLongEventsList() {
		return $this->_DP_obj->getLongEventsList();
	}


	public function getBrandsSamenamesList() {
		return $this->_DP_obj->getBrandsSamenamesList(self::$_user_language_id);
	}


	public function getExpocentersSamenamesList() {
		return $this->_DP_obj->getExpocentersSamenamesList(self::$_user_language_id);
	}

	public function getOrganizersSamenamesList() {
		return $this->_DP_obj->getOrganizersSamenamesList(self::$_user_language_id);
	}


	public function getSameEventsList() {
		return $this->_DP_obj->getSameEventsList();
	}


	public function getOrgsWithoutEmails() {
		return $this->_DP_obj->getOrgsWithoutEmails(self::$_user_language_id);
	}
}