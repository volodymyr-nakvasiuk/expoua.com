<?PHP

Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_TicketsModel extends Sab_Organizer_ModelAbstract {

    protected $_DP_name = 'List_Joined_Ep_Events';

    public function updateBuyerProgram($id_event) {
        $buyer = $this->getBuyerEntry($id_event);
        $db = DataproviderAbstract::getDatabaseObjectInstance();
        $mails = preg_split("/[\s,;]+/", stripslashes($_POST['notification_emails_csv']));
        $notification_emails_csv = '';
        foreach ($mails as $mail)
            if ( ($mail = trim($mail)) && preg_match("/[\w\d._-]+@([a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,4}$/", $mail))
                $notification_emails_csv .= $mail.',';
        $notification_emails_csv = substr($notification_emails_csv, 0, strlen($notification_emails_csv)-1);
        if (!$notification_emails_csv)
            return false;
        if ($notification_emails_csv != $buyer['notification_emails_csv'])
            $res1 = $db->update("ExpoPromoter_Opt.buyers", array('notification_emails_csv'=>$notification_emails_csv), "events_id='$id_event'");
        else
            $res1 = true;
        if ($_POST['notification_language']  && $_POST['notification_language'] != $buyer['notification_language'])
        {
            $buyer_entry = $this->getBuyerEntry($id_event);
            $id = $buyer_entry['id'];
            $res2 = $db->update("tickets.forms", array('notification_language'=>$_POST['notification_language']), "id_event='$id'");
        }
        else
            $res2 = 1;
        return $res1&$res2;
    }
    
    public function getFormPresets($list_events) {
        $ids = array();
        $events_by_buyer_id = array();
        foreach ($list_events['data'] as $event)
            if (isset($event['bp']) && isset($event['bp']['id']))   
            {
                $ids[] = $event['bp']['id'];
                $events_by_buyer_id[$event['bp']['id']] = $event;
            }
        $ids = implode(',', $ids);
        $lang = Zend_Registry::get("language_code");
        $q = "SELECT f.id_form, f.id_event, f.type, fd.name
        FROM tickets.forms AS f
        LEFT JOIN tickets.forms_data AS fd ON (fd.id_form=f.id_form AND fd.id_language='".$lang."')
        WHERE f.type='form_preset'";
        if ($ids)
            $q .= " OR f.id_event IN (".$ids.")";
        $presets = DataproviderAbstract::getDatabaseObjectInstance()->
                fetchAssoc($q, array());
        if ($ids)
            foreach ($presets as $key=>$preset)
                if ($preset['type'] == 'form' && $preset['id_event'] && ($event = $events_by_buyer_id[$preset['id_event']]) )
                    $presets[$key]['name'] = $event['name'] . ' (' . $event['date_from'] .' - ' . $event['date_to'] .')';
        return $presets;
    }

    public function addBuyerProgram($id_event) {
        $db = DataproviderAbstract::getDatabaseObjectInstance();
        /*$mails = preg_split("/[\s,;]+/", stripslashes($notification_emails_csv));
        $notification_emails_csv = '';
        foreach ($mails as $mail)
            if ( ($mail = trim($mail)) && preg_match("/[\w\d._-]+@([a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,4}$/", $mail))
                $notification_emails_csv .= $mail.',';
        $notification_emails_csv = substr($notification_emails_csv, 0, strlen($notification_emails_csv)-1);*/
        if (!$_POST['money'] || !$_POST['max_money'] || !$_POST['buyers'] || !$_POST['contact_name'] || !$_POST['contact_phone'] || !$_POST['contact_email'])
            return false;
        $buyer = array(
            'events_id'=>$id_event, 
            'money'=>$_POST['money'], 
            'max_money'=>$_POST['max_money'], 
            'buyers_required'=>$_POST['buyers'], 
            'money_request'=>$_POST['money'], 
            'max_money_request'=>$_POST['max_money'], 
            'buyers_request'=>$_POST['buyers'], 
            'contact_name'=>$_POST['contact_name'], 
            'contact_phone'=>$_POST['contact_phone'], 
            'contact_email'=>$_POST['contact_email']
        );
        if ($g = trim($_POST['geography']))
            $buyer['geography'] = $g;
        $res1 = $db->insert("ExpoPromoter_Opt.buyers", $buyer);

        $subject = 'An organizer has requested a buyer program. Event ID: '.$id_event;
        $bodyhtml = "Info:<br />\r\n";
        $bodyhtml .= "<table>\r\n";
        $bodyhtml .= "<tr><td>1 buyer charge: </td><td>".$buyer['money']."</td></tr>\r\n";
        $bodyhtml .= "<tr><td>Budget limit: </td><td>".$buyer['max_money']."</td></tr>\r\n";
        $bodyhtml .= "<tr><td>Buyers required: </td><td>".$buyer['buyers_required']."</td></tr>\r\n";
        $bodyhtml .= "<tr><td>Contact name: </td><td>".$buyer['contact_name']."</td></tr>\r\n";
        $bodyhtml .= "<tr><td>Contact phone: </td><td>".$buyer['contact_phone']."</td></tr>\r\n";
        $bodyhtml .= "<tr><td>Contact Email: </td><td>".$buyer['contact_email']."</td></tr>\r\n";
        $bodyhtml .= "<tr><td>Event ID</td><td>".$buyer['events_id']."</td></tr>\r\n";
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

        //var_dump($buyer);return false;
        return $res1;
        /*$id = $db->lastInsertId();
        $res2 = $db->insert("tickets.forms", array('id_event'=>$id, 'notification_language'=>$notification_language));
        return $res1&$res2;*/
    }

    public function getBuyerEntry($id_event){
        return DataproviderAbstract::getDatabaseObjectInstance()->
            fetchRow("SELECT b.*, f.notification_language, f.id_form FROM ExpoPromoter_Opt.buyers AS b INNER JOIN tickets.forms AS f ON f.id_event=b.id WHERE b.events_id='".$id_event."'");
    }

    public function getList($page = null, $sort = null, $search = null) {
        $tmp = new Zend_Db_Expr(">= CURDATE()");
        $dp = $this->_DP("List_Joined_Ep_BrandPlusEvent");
        
        $this->_DP_limit_params['date_to'] = $tmp;
        $events = $dp->
            getList(null, null, $this->_DP_limit_params, array('name' => 'ASC'));
        $ids = '';
        foreach ($events['data'] as $event)
            $ids .= $event['id'].',';
        $ids = substr($ids, 0, strlen($ids)-1);
        if ($ids)
        {
            /*$buyer_entries = DataproviderAbstract::getDatabaseObjectInstance()->
                fetchAssoc("
                SELECT b.events_id, b.id, b.active, b.money, f.notification_language, f.id_form
                FROM ExpoPromoter_Opt.buyers AS b
                INNER JOIN tickets.forms AS f ON f.id_event=b.id
                WHERE b.events_id in (".$ids.") GROUP by b.id", array());*/
            $buyer_entries = DataproviderAbstract::getDatabaseObjectInstance()->
                fetchAssoc("
                SELECT b.events_id, b.*
                FROM ExpoPromoter_Opt.buyers AS b
                WHERE b.events_id in (".$ids.") GROUP by b.id", array());
            //var_dump($buyer_entries);
            foreach ($events['data'] as $key=>$event)
                if (isset($buyer_entries[$key]))
                $events['data'][$key]['bp'] = $buyer_entries[$key];
        }
        return $events;
    }

    public function getUserInfo() {
        return $this->_user_session->operator;
    }
}
