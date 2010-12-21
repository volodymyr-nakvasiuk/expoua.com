<?PHP
require_once(PATH_CONTROLLERS . "/Admin/ListControllerAbstract.php");

class Admin_Ep_Pblbanners_TestController extends Admin_ListControllerAbstract {

  public function listAction() {
		$country_id  = $this->getRequest()->getUserParam("country", 52);
		$category_id = $this->getRequest()->getUserParam("category", null);
		
		$module_id = empty($category_id) ? 1 : 2;

    $this->_view->country  = $country_id;
    $this->_view->category = $category_id;

    $this->_view->lang = Zend_Registry::get('language_id');
  
    $this->_view->categories_list = $this->_model->getCategoriesList();
    $this->_view->banners_list    = $this->_model->getTestBanners($module_id, $category_id, $country_id);
  
    // parent::ListAction();
  }

}