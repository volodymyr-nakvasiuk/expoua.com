<?PHP
Zend_Loader::loadClass("Sab_Company_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Company_SgalleriesController extends Sab_Company_ControllerAbstract {

	public function listAction() {

		$search = $this->_prepareSearch();

		if (!empty($this->_user_param_parent)) {
			$search[] = array('column' => 'companies_services_id', 'value' => $this->_user_param_parent, "type" => '=');
		}

		$this->_view->list = $this->_model->getList($this->_user_page, $this->_user_param_sort, $search);
	}

}