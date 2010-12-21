<?PHP
Zend_Loader::loadClass("Sab_ListControllerAbstract", PATH_CONTROLLERS);

class Sab_JsonfeedsController extends Sab_ListControllerAbstract {

	protected function _initView() {
		Zend_Loader::loadClass("Admin_Feed_Json", PATH_VIEWS);
		$this->_view = new Admin_Feed_Json();
	}

	public function organizersAction() {
		$search = $this->_prepareSearch();
		$this->_view->list = $this->_model->getOrganizersList($this->_user_page, $this->_user_param_sort, $search);
	}
	
	public function organizerAction() {
		$this->_view->entry = $this->_model->getOrganizer($this->_user_param_id);
	}

	public function brandsAction() {
		$search = $this->_prepareSearch();

		$forceListResults = $this->getRequest()->getUserParam("results", null);
		if (!is_null($forceListResults)) {
			$this->_model->forceListResults = $forceListResults;
		}

		$this->_view->list = $this->_model->getBrandsList($this->_user_page, $this->_user_param_sort, $search);
	}

	public function eventsAction() {
		$search = $this->_prepareSearch();

		$forceListResults = $this->getRequest()->getUserParam("results", null);
		if (!is_null($forceListResults)) {
			$this->_model->forceListResults = $forceListResults;
		}

		$this->_view->list = $this->_model->getEventsList($this->_user_page, $this->_user_param_sort, $search);
	}

	public function brandscategoriesAction() {
		$forceListResults = $this->getRequest()->getUserParam("results", null);
		if (!is_null($forceListResults)) {
			$this->_model->forceListResults = $forceListResults;
		}

		$this->_view->list = $this->_model->getBrandsCategoriesList();
	}

	public function brandscategoryAction() {
		$this->_view->entry = $this->_model->getBrandsCategory($this->_user_param_id);
	}


	public function brandssubcategoriesAction() {
		$this->_view->list = $this->_model->getBrandsSubCategoriesList($this->_user_param_parent);
	}


	public function countriesAction() {
		$search = $this->_prepareSearch();

		$forceListResults = $this->getRequest()->getUserParam("results", null);
		if (!is_null($forceListResults)) {
			$this->_model->forceListResults = $forceListResults;
		}

		$this->_view->list = $this->_model->getCountriesList($this->_user_page, $this->_user_param_sort, $search);
	}

	public function countryAction() {
		$this->_view->entry = $this->_model->getCountry($this->_user_param_id);
	}

	public function citiesAction() {
		$search = $this->_prepareSearch();

		$forceListResults = $this->getRequest()->getUserParam("results", null);
		if (!is_null($forceListResults)) {
			$this->_model->forceListResults = $forceListResults;
		}

		$this->_view->list = $this->_model->getCitiesList($this->_user_page, $this->_user_param_sort, $search);
	}

	public function cityAction() {
		$this->_view->entry = $this->_model->getCity($this->_user_param_id);
	}

	public function servcategoriesAction() {
		$search = $this->_prepareSearch();
		$this->_view->list = $this->_model->getSocOrganizersList($this->_user_page, $this->_user_param_sort, $search);
	}

	public function expocentersAction() {
		$forceListResults = $this->getRequest()->getUserParam("results", null);
		if (!is_null($forceListResults)) {
			$this->_model->forceListResults = $forceListResults;
		}
		$search = $this->_prepareSearch();
		$this->_view->list = $this->_model->getExpocentersList($this->_user_page, $this->_user_param_sort, $search);
	}

	/**
	 * Передает последнее событие указанного в id бренда
	 *
	 */
	public function lasteventbybrandAction() {
		$this->_view->entry = $this->_model->getLastEventByBrand($this->_user_param_id);
	}

	public function checkcompanyuserAction() {
		$this->_view->entry = $this->_model->checkCompanyUser($this->_user_param_id);
	}

	public function companiesAction() {
		$search = $this->_prepareSearch();
		$this->_view->list = $this->_model->getCompaniesList($this->_user_page, $this->_user_param_sort, $search);
	}

	public function compservcatsAction() {
		$search = $this->_prepareSearch();
		$this->_view->list = $this->_model->getCompaniesServiceCatsList($this->_user_page, $this->_user_param_sort, $search);
	}


	//Переопределяем действия для исключения их использования
	public function listAction() {}
	public function editAction() {}
	public function addAction() {}
	public function updateAction() {}
	public function insertAction() {}
	public function deleteAction() {}

}