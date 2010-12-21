<?PHP
Zend_Loader::loadClass("Sab_Organizer_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Organizer_TicketsController extends Sab_Organizer_ControllerAbstract {

    public function viewAction() {
        $list_events = $this->_model->getList();
        $this->_view->list_events = $list_events;
        if (!empty($this->_user_param_id)) {
            
            $entry_event = $this->_model->getEntry($this->_user_param_id);
            $this->_view->entry_event = $entry_event;
            $entry_buyer = $this->_model->getBuyerEntry($this->_user_param_id);
            if ($entry_buyer)
                $this->_view->entry_buyer = $entry_buyer;
            else
            {
                // informing US about interest in program
                $org = $this->_view->organizer;
                $subject = 'An organizer has showed interest in buyer program. Event ID: '.$entry_event['id'];
                $bodyhtml = "Info:<br />\r\n";
                $bodyhtml .= "<table>\r\n";
                $bodyhtml .= "<tr><td colspan='2'><b>User Info:</b></td></tr>\r\n";
                if ($org && is_array($org))
                    foreach ($org as $name=>$param)
                        if ($param)
                            $bodyhtml .= "<tr><td>$name:</td><td>$param</td></tr>\r\n";
                $bodyhtml .= "\r\n<tr><td><b>User:</b></td><td>".$this->_view->session_user."</td></tr>\r\n";
                $bodyhtml .= "\r\n<tr><td colspan='2'><b>Event Info:</b></td></tr>\r\n\r\n";
                $bodyhtml .= "<tr><td>Name:</td><td>".$entry_event['brand_name']."</td></tr>\r\n";
                $bodyhtml .= "<tr><td>Id:</td><td>".$entry_event['id']."</td></tr>\r\n";
                $bodyhtml .= "<tr><td>Dates:</td><td>".$entry_event['date_from']." - ".$entry_event['date_to']."</td></tr>\r\n";
                $bodyhtml .= "</table>\r\n";
                try
                {
                    require_once("Zend/Mail.php");
                    $mailObj = new Zend_Mail("utf-8");
                    $mailObj->addTo(BUYER_NOTIFICATION_EMAIL, 'Expopromoter Buyer Management');
                    $mailObj->setFrom('no-reply@expopromoter.com', 'Expopromoter organizer admin site');
                    $mailObj->setSubject($subject);
                    $mailObj->setBodyHtml($bodyhtml);
                    $mailObj->setBodyText(strip_tags($bodyhtml));
            
                    @$mailObj->send();
                }
                catch (Exception $e)
                {
                    ;
                }
                //$this->
                //$this->_view->form_presets = $this->_model->getFormPresets($list_events);
            }
        }
    }

    public function indexAction() {
        $this->_helper->redirector->gotoUrl(
            $this->_view->getUrl(array('add' => 1, 'action' => 'view')));
    }
    public function editAction() {
        if (!empty($this->_user_param_id)) {
            $this->_view->event = $this->_model->getEntry($this->_user_param_id);
            $this->_view->buyer = $this->_model->getBuyerEntry($this->_user_param_id);
        }
    }
    public function updateAction() {
        if (!empty($this->_user_param_id)) {
            $this->_view->last_action_result = $this->_model->updateBuyerProgram($this->_user_param_id);
        }
    }
    public function listAction() {
        $this->indexAction();
    }
    public function insertAction() {
        if (!empty($this->_user_param_id)) {
            $this->_view->last_action_result = $this->_model->addBuyerProgram($this->_user_param_id);
        }
    }
    public function __call($name, $arguments) {
        $this->indexAction();
    }

}