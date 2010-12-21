<?PHP

Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_TicketsstatModel extends Sab_Organizer_ModelAbstract {

    protected $_DP_name = 'List_Joined_Ep_Events';

    private function _getFieldsList($events_id) {
        $lang = Zend_Registry::get("language_code");
        $fields_plain = DataproviderAbstract::getDatabaseObjectInstance()->
            fetchAll("SELECT ff.*, ffd.*
            FROM  tickets.forms AS f
            INNER JOIN tickets.forms_fields AS ff ON ff.id_form=f.id_form
            INNER JOIN tickets.forms_fields_data AS ffd ON ffd.id_field=ff.id_field AND ffd.id_language = ?
            INNER JOIN tickets.forms_fields_positions AS ffp ON ffp.id_field=ff.id_field
            WHERE f.id_event = ? ORDER BY ffp.position", array($lang, $events_id));
        $fields = array();
        $options_required = array();
        foreach ($fields_plain as $field)
        {
            if ($field['type'] == 'separator' || $field['type'] == 'htmlblock')
                continue;
            $field['config'] = json_decode($field['config']);
            $fields[$field['id_field']] = $field;
            if (!isset($field['config']->options) || count($field['config']->options)==0)
                $options_required[] = $field['type'];
        
        }
        if (count($options_required))
        {
            $fieldsOptions = $this->getFieldsOptions($options_required);
        }
        foreach ($fields as $key=>$field)
            if (isset($fieldsOptions[$field['type']]))
                $fields[$key]['config']->options = $fieldsOptions[$field['type']];/**/
        return $fields;
    }

    public function getDefaultFieldsList($events_id) {
        $lang = Zend_Registry::get("language_code");
        $fields_plain = DataproviderAbstract::getDatabaseObjectInstance()->
            fetchAll("SELECT ff.*, ffd.*
            FROM ExpoPromoter_Opt.buyers b
            INNER JOIN tickets.forms AS f ON f.id_event=b.id
            INNER JOIN tickets.forms_fields AS ff ON ff.id_form=f.id_form
            INNER JOIN tickets.forms_fields_data AS ffd ON ffd.id_field=ff.id_field AND ffd.id_language = ?
            INNER JOIN tickets.forms_fields_positions AS ffp ON ffp.id_field=ff.id_field
            WHERE b.events_id = ? AND ff.type LIKE 'default_%' ORDER BY ffp.position", array($lang, $events_id));
        $fields = array();
        foreach ($fields_plain as $field)
            $fields[$field['id_field']] = $field;
        return $fields;
    }

    public function getAllTicketsList($events_id, $sort = null) {
        $lang = Zend_Registry::get("language_code");
        $tickets = DataproviderAbstract::getDatabaseObjectInstance()->
            fetchAll("SELECT r.*, c.approve_status, c.reason, b.id
            FROM ExpoPromoter_Opt.buyers b
            INNER JOIN tickets.forms AS f ON f.id_event=b.id
            INNER JOIN tickets.registrations AS r ON (r.id_form = f.id_form)
            LEFT JOIN tickets.registrations_cancellations AS c ON c.id_registration=r.id_registration
            WHERE b.events_id = ? ORDER BY r.date DESC", array($events_id));
        if (!$tickets || ! is_array($tickets))
            return array();
        $fields = $this->_getFieldsList($tickets[0]['id']);
        $ticketsList = array();
        foreach ($tickets as $key=>$ticket)
        {
            $ticket['fields'] = json_decode($ticket['fields']);
            $datereg = array('en'=>'Registration date', 'ru'=>'Дата регистрации');
            $namedTicket = array(
                $datereg[$lang] => $ticket['date']
            );
            foreach ($fields as $field)
            {
                if (strpos($field['type'], 'default_') === 0)
                    $value = $ticket[substr($field['type'], 8)];
                elseif (isset($ticket['fields']->{$field['id_field']}))
                    $value = $ticket['fields']->{$field['id_field']};
                else
                    $value = '';
                if (!is_array($value))
                    $value = array($value);
                if (isset($field['config']) && isset($field['config']->options))
                    $options = $field['config']->options;
                else
                    $options = false;
                $vals = array();
                foreach ($value as $val)
                {
                    if (is_object($val))
                    {
                        if (isset($val->$lang))
                            $vals[] = $val->$lang;
                        else
                        {
                            $vars = array_values(get_object_vars($value));
                            $vals = $vars[0];
                        }
                    }
                    elseif ($options && $val && isset($options->$val) && isset($options->$val->$lang))
                    {
                        $vals[] = $options->$val->$lang;
                    }
                    else
                        $vals[] = $val;
                }
                $value = implode(', ', $vals);
                $namedTicket[$field['name']] = $value;
            }
            $ticketsList[] = $namedTicket;
        }
        return $ticketsList;
    }
    
    private function getFieldsOptions($fieldTypes) {
        $optString = implode(',', $fieldTypes);
            $timeout = 7;
        $opts = array(
          'http'=>array(
            'method'=>"GET",
            'timeout'=>$timeout
          )
        );
        $res = stream_context_create($opts);
        //stream_set_timeout($res, $timeout);
        try
        {
            $fieldsOptions = json_decode(file_get_contents(TICKETS_HOST.'/getFieldsOptions/'.$optString.'/', 0, $res));
        }
        catch (Exception $e)
        {
           ;// echo $e->getMessage() ; //
        }
        return (array)$fieldsOptions;
    }

    public function getTicketsList($events_id, $page = null, $sort = null) {
        if (!$page)
            $page = 0;
        else
            $page--;
        $tickets = DataproviderAbstract::getDatabaseObjectInstance()->
            fetchAll("SELECT r.id_registration, r.id_form, r.date, r.firstname as default_firstname,
            r.lastname as default_lastname, r.email as default_email, c.approve_status, c.reason
            FROM ExpoPromoter_Opt.buyers b
            INNER JOIN tickets.forms AS f ON f.id_event=b.id
            INNER JOIN tickets.registrations AS r ON (r.id_form = f.id_form)
            LEFT JOIN tickets.registrations_cancellations AS c ON c.id_registration=r.id_registration
            WHERE b.events_id = ? ORDER BY r.date DESC LIMIT ".($page*20).", 20", array($events_id));
        if ( ($page && $page>1) || ($cnt=count($tickets))==20 )
            $cnt = DataproviderAbstract::getDatabaseObjectInstance()->
                fetchOne("SELECT count(*)
                FROM ExpoPromoter_Opt.buyers b
                INNER JOIN tickets.forms AS f ON f.id_event=b.id
                INNER JOIN tickets.registrations AS r ON (r.id_form = f.id_form)
                LEFT JOIN tickets.registrations_cancellations AS c ON c.id_registration=r.id_registration
                WHERE b.events_id = ?", array($events_id));
        $pages = ceil($cnt/20);
        return array('list'=>$tickets, 'page'=>$page+1, 'pages'=>$pages);
    }

    public function getTicket($ticket_id) {
        $lang = Zend_Registry::get("language_code");
        $ticket = DataproviderAbstract::getDatabaseObjectInstance()->
            fetchRow("SELECT r.*, c.approve_status, c.reason, f.id_event
            FROM tickets.registrations AS r
            INNER JOIN tickets.forms AS f ON f.id_form=r.id_form
            LEFT JOIN tickets.registrations_cancellations AS c ON c.id_registration=r.id_registration
            WHERE r.id_registration = ?", array($ticket_id));
        $ticket['fields'] = json_decode($ticket['fields']);
        $fields = $this->_getFieldsList($ticket['id_event']);
        $ticket['fieldClasses'] = $fields;
        $ticket['namedValues'] = array();
        foreach ($fields as $field)
        {
            if (strpos($field['type'], 'default_') === 0)
                $value = $ticket[substr($field['type'], 8)];
            elseif (isset($ticket['fields']->{$field['id_field']}))
                $value = $ticket['fields']->{$field['id_field']};
            else
                continue;
            if (!is_array($value))
                $value = array($value);
            if (isset($field['config']) && isset($field['config']->options))
                $options = $field['config']->options;
            else
                $options = false;
            $vals = array();
            foreach ($value as $val)
            {
                if (is_object($val))
                {
                    if (isset($val->$lang))
                        $vals[] = $val->$lang;
                    else
                    {
                        $vars = array_values(get_object_vars($value));
                        $vals = $vars[0];
                    }
                }
                elseif ($options && $val && isset($options->$val) && isset($options->$val->$lang))   
                {
                    $vals[] = $options->$val->$lang;
                }
                else
                    $vals[] = $val;
            }
            $value = implode(', ', $vals);
            $ticket['namedValues'][$field['name']] = $value;
        }
        return $ticket;
    }

    public function requestDenial($ticket_id, $reason) {
        return DataproviderAbstract::getDatabaseObjectInstance()->
        insert("tickets.registrations_cancellations", array('id_registration'=>$ticket_id, 'reason'=>urldecode($reason)));
    }

    public function getList($page = null, $sort = null, $search = null) {
        $events = $this->_DP("List_Joined_Ep_BrandPlusEvent")->
            getList(null, null, $this->_DP_limit_params, array('name' => 'ASC'));
        $ids = '';
        foreach ($events['data'] as $event)
            $ids .= $event['id'].',';
        $ids = substr($ids, 0, strlen($ids)-1);
        $forms = DataproviderAbstract::getDatabaseObjectInstance()->
            fetchAll("
            SELECT b.id, b.active, b.money, b.buyers_required, b.max_money, count(r.id_registration) as num_registrations, count(rc.id_registration) AS num_declined, f.id_form, b.events_id, IFNULL(0, t.deposit) as deposit 
            FROM ExpoPromoter_Opt.buyers AS b
            LEFT JOIN (SELECT id_buyer, SUM(summ) AS deposit FROM ExpoPromoter_Opt.transactions GROUP BY id_buyer) AS t ON t.id_buyer=b.id
            LEFT JOIN tickets.forms AS f ON f.id_event=b.id
            LEFT JOIN tickets.registrations AS r USING (id_form)
            LEFT JOIN tickets.registrations_cancellations AS rc ON (rc.id_registration=r.id_registration AND rc.approve_status=1)
            WHERE b.events_id in (".$ids.") GROUP by b.id", array());
        if (is_array($forms))
            foreach ($forms as $key=>$form)
                $forms[$key]['event'] = $events['data'][$form['events_id']];
        else
            $forms = array();
        return $forms;
    }

    public function getUserInfo() {
        return $this->_user_session->operator;
    }
}
