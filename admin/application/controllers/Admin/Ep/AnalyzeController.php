<?PHP

Zend_Loader::loadClass("Admin_ControllerAbstract", PATH_CONTROLLERS);

class Admin_Ep_AnalyzeController extends Admin_ControllerAbstract {

	public function indexAction() {

	}

	public function nofutureAction() {

		$country = $organizer = null;
		$year = $this->getRequest()->getPost("year", null);
		$year = (empty($year) ? null:$year);

		$search = $this->_prepareSearch();

		if (is_array($search)) {
			foreach ($search as $el) {
				if ($el['column'] == 'countries_id') {
					$country = $el['value'];
				}

				if ($el['column'] == 'organizer_name') {
					$organizer = $el['value'];
				}
			}
		}

		$this->_view->list = $this->_model->getBrandsWithoutFutureEvents($year, $country, $organizer);
	}

	public function noeventsAction() {
		$this->_view->list = $this->_model->getBrandsWithoutEvents();
	}

	public function nobrandsAction() {
		$this->_view->list = $this->_model->getOrgsWithoutBrands();
	}

	public function longeventsAction() {
		$this->_view->list = $this->_model->getLongEventsList();
	}

	public function samenamesAction() {
		$this->_view->list = $this->_model->getBrandsSamenamesList();
	}

	public function sameecnamesAction() {
		$this->_view->list = $this->_model->getExpocentersSamenamesList();
	}

	public function sameorgnamesAction() {
		$this->_view->list = $this->_model->getOrganizersSamenamesList();
	}

	public function sameeventsAction() {
		$this->_view->list = $this->_model->getSameEventsList();
	}

	public function orgswoemailsAction() {
		$this->_view->list = $this->_model->getOrgsWithoutEmails();
	}
}