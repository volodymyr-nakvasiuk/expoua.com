<?PHP
require_once(PATH_CONTROLLERS . "/Admin/ListControllerAbstract.php");

class Admin_Ep_Operators_MessagesController extends Admin_ListControllerAbstract {

	public function editAction() {
		parent::editAction();

		$this->_view->list = $this->_model->getList(1, null, array(),
			array(array('column' => 'users_operators_id', 'value' => $this->_user_param_parent, 'type' => '=')));
	}

}