<?PHP
require_once(PATH_CONTROLLERS. "/Admin/ListControllerAbstract.php");

class Admin_Ep_Buyers_EntriesController extends Admin_ListControllerAbstract {
	
	public function insertAction() {
		if ($this->getRequest()->getUserParam("type") == "transactions") {
			$data = $this->getRequest()->getPost();
			
			if (empty($data) || empty($data['summ'])) {
				$this->_setLastActionResult(-3);
				return;
			}
			
			$res = $this->_model->insertTransaction($this->_user_param_id, $data);
			$this->_setLastActionResult($res);
		} else {
			parent::insertAction();
		}
	}
	
}