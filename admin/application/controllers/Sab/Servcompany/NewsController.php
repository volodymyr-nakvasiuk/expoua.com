<?PHP
Zend_Loader::loadClass("Sab_Servcompany_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Servcompany_NewsController extends Sab_Servcompany_ControllerAbstract {

	public function addAction() {
		
		$this->_view->list_brand_categories = $this->_model->getBrandCategoriesList();
		$this->_view->list_countries = $this->_model->getCountriesList();
	}
	
	/**
	 * Элементарное действие добавления записи
	 *
	 */
	public function insertAction() {
		
		$data = $this->getRequest()->getPost();

		if (empty($data)) {
			$this->_setLastActionResult(-3);
			return;
		}

		$res = $this->_model->insertEntry($data, true);

		$this->_setLastActionResult($res);
		
	}
	
	public function previewAction() {
		$this->_view->setTemplate('controllers_frontend/sab_servcompany_news/preview.tpl');
		$data = $this->getRequest()->getPost();

		$this->_view->data = $data;
	}

	
}