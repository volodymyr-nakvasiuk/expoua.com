<?PHP
require_once(PATH_CONTROLLERS . "/Admin/ListControllerAbstract.php");

class Admin_Ep_Partners_MessagesController extends Admin_ListControllerAbstract {

  public function editAction() {
    parent::editAction();

    $this->_view->list = $this->_model->getList(1, null, array(),
      array(array('column' => 'partners_id', 'value' => $this->_user_param_parent, 'type' => '=')));
  }

}