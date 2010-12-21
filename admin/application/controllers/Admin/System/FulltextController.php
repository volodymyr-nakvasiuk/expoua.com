<?PHP

require_once (PATH_CONTROLLERS . "/Admin/ControllerAbstract.php");

class Admin_System_FulltextController extends Admin_ControllerAbstract {

	public function indexAction() {
	}
	
	public function listAction() {
	}
	
	public function viewAction() {
		switch ($this->_user_param_id) {
			case 'cms_pages':
				$this->_view->entry = $this->_model->getCmsPagesStat();
				break;
			case 'companies':
				$this->_view->entry = $this->_model->getCompaniesStat();
				break;
			case 'companies_services':
				$this->_view->entry = $this->_model->getCompaniesServicesStat();
				break;
			default:
				$this->_forward('index');
		}
	}
	
}