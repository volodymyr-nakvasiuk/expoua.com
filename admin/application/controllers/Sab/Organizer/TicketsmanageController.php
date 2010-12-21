<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_TicketsmanageController extends Sab_Organizer_ControllerAbstract {

    public function viewAction() {
        $regId = $this->getRequest()->getUserParam("registrationId");
        if (!empty($regId)) {
            $this->_view->ticket = $this->_model->getTicket($regId);
        }
    }

    public function indexAction() {
        $this->_helper->redirector->gotoUrl(
            $this->_view->getUrl(array('add' => 1, 'action' => 'list')));
    }
    public function editAction() {
        $this->indexAction();
    }
    public function updateAction() {
        $regId = $this->getRequest()->getUserParam("registrationId");
        $reason = $this->getRequest()->getUserParam("reason");
        if (!empty($regId) && !empty($reason) ) {
            $this->_view->last_action_result = $this->_model->requestDenial($regId, $reason);
        }
    }
    public function listAction() {
        if (($feed=$this->getRequest()->getUserParam("feed")) && ($feed=='csv')) {
            if (!empty($this->_user_param_id)) {
                $this->_view->list = array('data'=>$this->_model->getAllTicketsList($this->_user_param_id));
            }
        }
        else
        {
            //$this->_view->entry_user = $this->_model->getUserInfo();
            if (!empty($this->_user_param_id))
            {
                $entry_event = $this->_model->getEntry($this->_user_param_id);
                $this->_view->entry_event = $entry_event;
                $this->_view->list_fields = $this->_model->getDefaultFieldsList($this->_user_param_id);
                $this->_view->tickets = $this->_model->getTicketsList($this->_user_param_id, $this->_user_page);
            }
            else
            {
                $buyerPrograms = $this->_model->getList();
                $this->_view->list_buyerPrograms = $buyerPrograms;
                $form_ids = array();
                foreach ($buyerPrograms as $bp) 
                    if ($bp['id_form'])
                        $form_ids[] = $bp['id_form'];
                $this->_view->prev_forms_csv = implode(',', $form_ids);
                if (isset($_COOKIE['__a_k']))
                    apc_delete($_COOKIE['__a_k']);
                $apc_var_name = uniqid('form_ids');
                apc_store($apc_var_name, $form_ids, 10800);
                setcookie('__a_k', $apc_var_name, 0, '/', '.'.TOP_DOMAIN);
            }
        }
    }
    public function insertAction() {
        $this->indexAction();
    }
    public function __call($name, $arguments) {
        $this->indexAction();
    }

}