<?PHP
require_once(PATH_CONTROLLERS . "/Admin/ListControllerAbstract.php");

class Admin_Ep_Pblbanners_UsersController extends Admin_ListControllerAbstract {

	public function listAction() {
	  // Zend_Debug::dump($this->_user_param_sort);

	  $this->_user_param_sort = $this->_user_param_sort ? $this->_user_param_sort : array('id' => "DESC");
	  parent::listAction();
	} 

}