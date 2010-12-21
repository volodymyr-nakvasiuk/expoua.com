<h3>{#selectEvent#}:</h3>
<select onchange="document.location.href='{getUrl add=1 del="id"}id/' + this.value + '/';">
<option value=""{if !$el.id} selected="selected"{/if}>{#selectEventOption#}</option>
{foreach from=$list_events.data item="el"}
 <option value="{$el.id}"{if $el.id==$entry_event.id} selected="selected"{/if}>{$el.name|escape:"html"}&nbsp;({$el.date_from} - {$el.date_to})</option>
{/foreach}
</select>

{if $entry_event}
<br /><br /><br /><br /><br />
<style>
{literal}
.datepicker  {
background:inherit;
font-size:inherit;
text-align:inherit;
}
.datepicker_control, .datepicker_links, .datepicker_header, .datepicker {
clear:inherit;
color:inherit;
float:inherit;
width:inherit;
}
.widget_colorpicker{width: 70px;}
.number{width: 50px;}
.fieldTitle {color: #555}
.inputTD {white-space: nowrap;}
.actionTD {vertical-align: top;}
.error {border: 2px solid #fee8a0; background-color: #fdf4d5; color:#444; padding: 7px;}
.success {border: 2px solid #cefea9; background-color: #e8fdd9; color:#444; padding: 7px;}
.tip { }
{/literal}
</style>
<script type="text/javascript">
    $(document).unbind('ready');
</script>
<script src="/js/jquery142.js"></script>
<link type="text/css" href="/css/colorpicker.css" rel="stylesheet"/>
<script src="/js/jqExtensions/colorpicker.new.js"></script>
<script src="/js/jqExtensions/jquery.cluetip.js"></script>
<script type="text/javascript">

    var $142 = $.noConflict(true);
    
    var booking_host = '{$smarty.const.PROPRIETARY_BOOKING_HOST}';
    var date_from = '{$entry_event.date_from}';
    var date_from_parts = date_from.split('-');
    var date_from_UTC = Date.UTC(parseInt(date_from_parts[0],10),parseInt(date_from_parts[1],10)-1,parseInt(date_from_parts[2],10));
    var date_to = '{$entry_event.date_to}';
    var date_to_parts = date_to.split('-');
    var date_to_UTC = Date.UTC(parseInt(date_to_parts[0],10),parseInt(date_to_parts[1],10)-1,parseInt(date_to_parts[2],10));
    var nights = Math.round((date_to_UTC - date_from_UTC) / 86400000) + 1;
    
    var event_id = '{$entry_event.id}';
    var locationInSelectedLanguage = '{$entry_location[$selected_language].city}, {$entry_location[$selected_language].country}';
    var city = '{$entry_location.en.city|escape:"url"}';
    var cityGeoName = '{$entry_location.en.geocityname|escape:"url"}';
    if (cityGeoName)
        city = cityGeoName;
    var country = '{$entry_location.en.country}';
    var countryCode = '{$entry_location.en.code}';
    var language = '{$selected_language}';
    var timeoutFaultMessage = '{#timeoutFaultMessage#}';
    var mail_params = {ldelim}{rdelim};
    mail_params['eventName'] = '{$entry_event.brand_name}';
    mail_params['organizerId'] = '{$organizer.id}';
    mail_params['operatorId   '] = '{$entry_user->id}';
    mail_params['operatorLogin'] = '{$entry_user->login}';
    mail_params['operatorName '] = '{$entry_user->name_fio}';
    mail_params['operatorEmail'] = '{$entry_user->email}';
    mail_params['operatorPhone'] = '{$entry_user->phone}';
    var thisHost = '{$smarty.server.HTTP_HOST}';
    
    var aid = '{$entry_affiliate.id}'; 
    if (!aid)
        aid = 2; //expopromoter id
    var savedCssString = "{$entry_affiliate.widget_css|escape:javascript}";
    var savedWpString = "{$entry_affiliate.widget_params|escape:javascript}";
    var code = '';
    var cssString = '';
        

{literal}

    var css_list = {};
    var newWidgetParams = {};
    var saveWidgetParams = {};
    var defaults = {};
    defaults['rooms'] = 1;
    defaults['ppr'] = 1;
    defaults['dateIn'] = date_from;
    defaults['dateOut'] = date_to;
    defaults['header_url'] = 'http://expopromoter.com/EventInfo/lang/en/id/'+event_id+'/';
    var map_url_orig = '';
    var cool_button_elements = '';
    var button_text = '';
    var timed_out = false;
    var saveTimeout = false;
    var non_default_params = false;

  (function ($) {    
    form_widget_code = function (save)
    {
        timed_out = false;
        $('#widget_config_success').hide();
        $('#widget_config_error').hide();

        dates = booking_form_datepicker.DatePickerGetDate(false);
        dateIn  = dates[0].getTime();
        dateOut = dates[1].getTime();
        var m = (dates[0].getMonth() + 1);
        var d = dates[0].getDate();
        dateInNew =  dates[0].getFullYear() + '-' + (m > 9 ? m : '0' + m) + '-' + (d > 9 ? d : '0' + d);
        var m = (dates[1].getMonth() + 1);
        var d = dates[1].getDate();
        dateOutNew =  dates[1].getFullYear() + '-' + (m > 9 ? m : '0' + m) + '-' + (d > 9 ? d : '0' + d);
        if (!dateInNew)
            dateInNew = date_from;
        else
        {
            saveWidgetParams['dateIn'] = dateInNew;
            saveWidgetParams['dateOut'] = dateOutNew;
        }
        nightsNew = Math.floor((dateOut - dateIn) / 86400000);
        if (!nightsNew)
            nightsNew = nights;
        roomsNew = $('#epbs-booking-form .rooms .target').val();
        if (!roomsNew)
            roomsNew=1;
        else
            saveWidgetParams['rooms'] = roomsNew;
        pprNew = $('#epbs-booking-form .per-room .target').val();
        if (!pprNew)
            pprNew=1;
        else
            saveWidgetParams['ppr'] = pprNew;
            lang = $('.lang').filter(':checked').val();

        if (!lang)
            lang = language;

        if (!saveWidgetParams['header_url'])
            saveWidgetParams['header_url'] = defaults['header_url'];


        wpString = '';
        for (i in newWidgetParams)
            wpString += i + '=' + newWidgetParams[i] + '|';
        saveWpString = wpString;
        for (i in saveWidgetParams)
        {
            if (saveWidgetParams[i] != defaults[i])
                saveWpString += i + '=' + saveWidgetParams[i] + '|';
        }
        if (wpString)
            wpString = wpString.substr(0, (wpString.length)-1);
        if (saveWpString)
            saveWpString = saveWpString.substr(0, (saveWpString.length)-1);
        cssString = '';
        for (i in css_list)
        {
            numEl = 0;
            for (k in css_list[i])
                numEl++;
            if (numEl)
            {
                cssString += i + '{ ';
                for (k in css_list[i])
                    if (css_list[i][k])
                        cssString += k + ':' + css_list[i][k] + ';';
                cssString += "}\r\n";
            }
        }

        if (save && (!aid || aid=="2" || (cssString != savedCssString) || (savedWpString != saveWpString) ) )
        {
            save_config(cssString, saveWpString);
            $('#activityIndicator').show();
            saveTimeout = setTimeout('save_timeout();', 10000);
            $('#sendButton').unbind('click').attr('disabled', 'disabled');
        }
            
        cssURLString = '';
        if (cssString)
            cssURLString = '\r\n<style type="text/css">\r\n' + cssString + '<\/style>';
        
        code = '<link href="'+booking_host+'/css/default_widget.css" media="screen,projection" rel="stylesheet" type="text/css" />\r\n';
        code += '<script type="text/javascript" language="javascript" src="'+booking_host+'/' + lang + '/popup/'+aid+'/hotels/'+countryCode+'/'+city+'/'+dateInNew+'/'+nightsNew+'/'+roomsNew+'-'+pprNew+'/USD/searchform/' + 
        (wpString?encodeURIComponent(wpString) + '/':'') + 
        '?qs=' + encodeURIComponent('header:' + saveWidgetParams['header_url'].replace('/en/', '/'+lang+'/')) + 
        '"><\/script>\r\n';
        if (aid && (aid!=2))
            $('#code_area').val(code + cssURLString);
    }
    
    clear_widget_code = function()
    {
        $('#code_area').val('');
    }
    
    get_info_email_data = function ()
    {
        var subject = 'New organizer WIDGET code created';
        var body = '';
        body += '<table border="0" width="100%">\r\n';
        url = 'http://'+thisHost.replace('event', 'admin')+'/en/admin_acl_organizers/edit/id/'+mail_params['organizerId']+'/';
        body += '<tr class="non-default-design"><td>Admin organizer login page:</td><td><a href="'+url+'">'+url+'</a></td></tr>\r\n';
        for (i in mail_params)
            body += '<tr class="non-default-design"><td>'+i+' :</td><td>'+mail_params[i]+'</td></tr>\r\n';
        body += '</table>';
        return 'subject='+subject+'&body='+encodeURIComponent(body);
    }
    
    function save_config(cssString, wpString)
    {
        data = '';
        //if (cssString)
            data += 'widget_css=' + encodeURIComponent(cssString) + '&';
        //if (wpString)
            data += 'widget_params=' + encodeURIComponent(wpString) + '&';
        if (aid && (aid!=2))
            data += 'affiliate_id='+aid + '&';
        else
            data += 'external_id='+event_id + '&affiliate_type=org&';
        
        if (!aid || (aid==2))
            data += 'info_mail=true&'+get_info_email_data()+'&';
            
        data = data.substr(0, (data.length)-1);
        
        url = booking_host + '/' + language + '/hotels/searchform/save/';
        
        $.ajax({'url':url, 'data':data, 'type':'GET', dataType: 'jsonp', 'success':save_config_callback});
    }
    
    save_timeout = function ()
    {
        timed_out = true;
        $('#activityIndicator').hide();
        $('#widget_config_error').html(timeoutFaultMessage).show();
        $('#sendButton').click(function() {form_widget_code(true);}).removeAttr('disabled');
    }
    
    save_config_callback = function (result, status)
    {
        if (timed_out == true)
            return;
        $('#activityIndicator').hide();
        clearTimeout(saveTimeout);
        $('#sendButton').click(function() {form_widget_code(true);}).removeAttr('disabled');
        if (!aid || (aid==2) && result.id)
        {
            aid = result.id;
            code = code.replace(/(popup\/)\d+/, '$1'+result.id);
        }
        if (result.success)
            $('#widget_config_success').html(result.success).show();
        else if (result.fault)
            $('#widget_config_error').html(result.fault).show();
        $('#code_area').val(code + cssURLString);
    }

    apply = function (id, param, val)
    {   
        if (val &&  (param.search(/width/)>-1) && (val.toString().search(/\D/)==-1) )
            val = val+'px';
        $(id).css(param, val);
        if (!css_list[id])
            css_list[id] = {};
        if (val)
            css_list[id][param] = val;
        else if (css_list[id][param])
        {
            delete css_list[id][param];
        }
        clear_widget_code();
    }

    apply_map_width = function (val)
    {
        $('#epbs-booking-form .epbs-map-holder').css('display', '');
        if ( ( typeof map_url_orig=='undefined' ) || !map_url_orig )
            map_url_orig = $('#epbs-booking-form .epbs-map-holder').css('background-image');
        if ( ( typeof map_width_orig=='undefined' ) || !map_width_orig )
            map_width_orig = $('#epbs-booking-form .epbs-map-holder').css('width');
        if (valInt = parseInt(val,10))
        {
            $('#epbs-booking-form .epbs-map-holder').css('width', valInt+'px');
            map_url = map_url_orig.replace(/(size=)\d+/i, '$1'+(valInt+10));
            $('#epbs-booking-form .epbs-map-holder').css('background-image', map_url);
        }
        else if (val == '0')
        {
            $('#epbs-booking-form .epbs-map-holder').css('display', 'none');
        }
        else
        {
            $('#epbs-booking-form .epbs-map-holder').css('width', map_width_orig);
            $('#epbs-booking-form .epbs-map-holder').css('background-image', map_url_orig);
        }

        newWidgetParams['mapWidth'] = val;
        clear_widget_code();
    }

    apply_head_bg_color = function (val)
    {
        if (val)
        {
            apply('#epbs-booking-form .epbs-head', 'background', 'url("")');
            apply('#epbs-booking-form .epbs-head', 'background-color', val);
        }
        else
        {
            apply('#epbs-booking-form .epbs-head', 'background', '');
            apply('#epbs-booking-form .epbs-head', 'background-color', '');
        }
        clear_widget_code();
    }

    apply_search_button_bg_color = function (val)
    {
        if (!cool_button_elements)
            cool_button_elements = $('#epbs-booking-form .bg');
        apply('#epbs-booking-form a.epbs-cool-button', 'background-color', val);
        if (val)
            apply('#epbs-booking-form .epbs-cool-button .epbs-bg', 'background-image', 'url("")');
        else
            apply('#epbs-booking-form .epbs-cool-button .epbs-bg', 'background-image', '');
        clear_widget_code();
    }

    apply_search_button_text = function (val)
    {
        if (val)
        {
            if (!button_text)
                button_text = $('#epbs-booking-form .epbs-cool-button .epbs-value').text();
            $('#epbs-booking-form .epbs-cool-button .epbs-value').text(val);
            newWidgetParams['buttonText'] = val;
        }
        else if (button_text)
        {
            $('#epbs-booking-form .epbs-cool-button .epbs-value').text(button_text);
            delete newWidgetParams['buttonText'];
        }
        clear_widget_code();
    }
    
    apply_header_url = function(val)
    {
        if (!val)
        {
            val = defaults['header_url'];
        }
        saveWidgetParams['header_url'] = val;
    }
    
    form_reset = function () 
    {
        $('#widget_builder').get(0).reset();
        $('#widget_builder input').each(
            function (i, el)
            {
                if (el.onchange)
                    el.onchange();
            }
        );
        css_list = {};
        newWidgetParams = {};
    }
    
    apply_lang = function (lang)
    {
        $('#epbs-booking-form').detach();
        previews[lang].appendTo('#widget_preview_container');
        booking_form_datepicker.DatePickerHide();
        if (!datepickers[lang])
            init_widget();
        else
            booking_form_widget = datepickers[lang];
        apply_visual_params();
        clear_widget_code();
    }
    
  })($142);
    
{/literal}
</script>
  <h3 style="display: inline">{#WidgetPreview#}:</h3>&nbsp;&nbsp;{*include file="common/tip.tpl" topic="widget_preview" page="sab_organizer_bookingwidget"*}<br /><br />
  <link href="{$smarty.const.PROPRIETARY_BOOKING_HOST}/css/default_widget.css" media="screen,projection" rel="stylesheet" type="text/css" />
<div id="widget_preview_container" style="display:none;">
<script type="text/javascript">
    wLoc = '{$smarty.const.PROPRIETARY_BOOKING_HOST}/en/popup/2/hotels/'+countryCode+'/'+city+'/'+date_from+'/'+nights+'/1-1/USD/searchform/';
    document.write('<script src="'+ wLoc + 'preview/"><\/script>');
    document.write('<script src="'+ wLoc.replace('/en/', '/ru/') + 'preview/"><\/script>');
    document.write('<script src="'+ wLoc + 'callScripts/"><\/script>');
</script>
</div>
  <br /><br />
  <h3 style="display: inline">{#customSetup#}:</h3><br /><br />
  <div class="error" id="widget_config_error" style="display:none"></div>
  <div class="success" id="widget_config_success" style="display:none"></div>

<p>
  <input type="checkbox" id="default_design" value="1" checked="checked"/>&nbsp;
  <label for="default_design">{#captionDefaultDesign#}</label>
  &nbsp;&nbsp;{*include file="common/tip.tpl" topic="custom_setup" page="sab_organizer_bookingwidget"*}
</p>

  <form name="widget_builder" id="widget_builder" action="#">
  <table>
    <tr>
        <td colspan="6" class="tip">
            {#tip_select_language#}
        </td>
    </tr>
    <tr>
        <td class="fieldTitle">
            {#language#}
        </td>
        <td class="inputTD" colspan="5">
            <input type="radio" name="lang" class="lang" value="en" onclick="apply_lang(this.value)"{if $selected_language=='en'} checked="checked"{/if}>&nbsp;en
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="lang" class="lang" value="ru" onclick="apply_lang(this.value)"{if $selected_language=='ru'} checked="checked"{/if}>&nbsp;ru
        </td>
    </tr>
    <tr class="non-default-design">
        <td colspan="6" class="tip">
            {#tip_select_widget_params#}<br />
            *&nbsp;{#tip_ppr#}
        </td>
    </tr>
    <tr class="non-default-design">
        <td class="fieldTitle">
            {#widgetWidth#}
        </td>
        <td class="inputTD">
            <input type="text" class="number" onChange="apply('#epbs-booking-form', 'width', this.value)" id="epbs-booking-form_width">
        </td>
        <td class="fieldTitle">
            {#mapWidth#}
        </td>
        <td class="inputTD">
            <input type="text" class="number" onChange="apply_map_width(this.value)" id="mapWidth">&nbsp;px
        </td>
        <td class="fieldTitle">
            {#widgetBorder#}
        </td>
        <td class="inputTD">
            <input type="text" class="widget_colorpicker" onChange="apply('#epbs-booking-form', 'border-color', this.value)" id="epbs-booking-form_border-color">
        </td>
    </tr>
    <tr class="non-default-design">
        <td class="fieldTitle">
            {#headBgColor#}
        </td>
        <td class="inputTD">
            <input type="text" class="widget_colorpicker" onChange="apply_head_bg_color(this.value)" id="epbs-booking-form_epbs-head_background-color">
        </td>
        <td class="fieldTitle">
            {#headTextColor#}
        </td>
        <td class="inputTD">
            <input type="text" class="widget_colorpicker" onChange="apply('#epbs-booking-form .epbs-head', 'color', this.value)" id="epbs-booking-form_epbs-head_color">
        </td>
        <td class="fieldTitle">
            {#headBorderColor#}
        </td>
        <td class="inputTD">
            <input type="text" class="widget_colorpicker" onChange="apply('#epbs-booking-form .epbs-head', 'border-color', this.value)" id="epbs-booking-form_epbs-head_border-color">
        </td>
    </tr>
    <tr class="non-default-design">
        <td class="fieldTitle">
            {#widgetBgColor#}
        </td>
        <td class="inputTD">
            <input type="text" class="widget_colorpicker" onChange="apply('#epbs-booking-form ', 'background-color', this.value)" id="epbs-booking-form_background-color">
        </td>
        <td class="fieldTitle">
            {#fieldTextColor#}
        </td>
        <td class="inputTD">
            <input type="text" class="widget_colorpicker" onChange="apply('#epbs-booking-form .epbs-form .epbs-name', 'color', this.value)" id="epbs-booking-form_epbs-form_epbs-name_color">
        </td>
        <td>&nbsp;</td><td>&nbsp;</td>
    </tr>
    <tr class="non-default-design">
        <td class="fieldTitle">
            {#searchButtonBgColor#}
        </td>
        <td class="inputTD">
            <input type="text" class="widget_colorpicker" onChange="apply_search_button_bg_color(this.value)" id="epbs-booking-form_aepbs-cool-button_background-color">
        </td>
        <td class="fieldTitle">
            {#searchButtonTextColor#}
        </td>
        <td class="inputTD">
            <input type="text" class="widget_colorpicker" onChange="apply('#epbs-booking-form .epbs-button .epbs-content-wrapper .epbs-value', 'color', this.value)" id="epbs-booking-form_epbs-button_epbs-content-wrapper_epbs-value_color">
        </td>
        <td class="fieldTitle">
            {#searchButtonText#}
        </td>
        <td class="inputTD">
            <input type="text" onChange="apply_search_button_text(this.value)" id="buttonText">
        </td>
    </tr>
    <tr class="non-default-design">
        <td class="fieldTitle">
            {#providersBgColor#}
        </td>
        <td class="inputTD">
            <input type="text" class="widget_colorpicker" onChange="apply('#epbs-booking-form .epbs-logos', 'background-color', this.value)" id="epbs-booking-form_epbs-logos_background-color">
        </td>
        <td class="fieldTitle">
            {#providersBorderColor#}
        </td>
        <td class="inputTD">
            <input type="text" class="widget_colorpicker" onChange="apply('#epbs-booking-form .epbs-logos', 'border-color', this.value)" id="epbs-booking-form_epbs-logos_border-color">
        </td>
        <td>&nbsp;</td><td>&nbsp;</td>
    </tr>
    {*<tr class="non-default-design">
        <td class="fieldTitle">
            {#headerUrl#}
        </td>
        <td colspan="5">
            <input type="text" id="header_url" size="84" onChange="apply_header_url()">
        </td>
    </tr>*}
    <input type="hidden" id="header_url" size="84">
    <tr class="non-default-design">
        <td colspan="6">
            <a href="#" onClick="form_reset()">{#resetButton#}</a>
        </td>
    </tr>
    <tr>
        <td class="actionTD" width="5%">
            <input id="sendButton" type="button" value="{#submitButton#}">
            <span id="activityIndicator" style="display:none;"><img src="/img/ActivityIndicator.gif"></span>
            <br><div style="padding: 7px;">* {#tip_generate_button#}</div>
        </td>
        <td colspan="5">
            <textarea rows="17" cols="63" id="code_area"></textarea>
        </td>
    </tr>
  </table>
<script>
{literal}
    
    var previews = {};
    var datepickers = {};
    
    (function ($) {
        
        restore_visual_params = function ()
        {
            cssEntries = savedCssString.split('}');
            for (i in cssEntries)
            {
                if (cssEntries[i].replace(/\s/g, '') == '')
                    continue;
                cssEntryParts = cssEntries[i].split('{', 2);
                cssEntryName = cssEntryParts[0].replace(/(^\s*)|(\s*$)/g, '');
                cssParams    = cssEntryParts[1].split(';');
                for (k in cssParams)
                    if (cssParams[k].replace(/(^\s*)|(\s*$)/g, ''))
                    {
                        cssParamParts = cssParams[k].split(':');
                        cssParamName = cssParamParts[0].replace(/(^\s*)|(\s*$)/g, '');
                        cssParamVal  = cssParamParts[1].replace(/(^\s*)|(\s*$)/g, '');
                        apply(cssEntryName, cssParamName, cssParamVal);
                        input_id = (cssEntryName+'_'+cssParamName).replace(/[#\.]/g,'').replace(/\s/g, '_');
                        if (input = $('#'+input_id))
                            input.val(cssParamVal);
                    }
            }
            wpEntries = savedWpString.split('|');
            for (i in wpEntries)
            {
                if (wpEntries[i].replace(/\s/g, '')=='')
                    continue;
                entryParts = wpEntries[i].split('=', 2);
                entryName = entryParts[0].replace(/(^\s*)|(\s*$)/g, '');
                entryVal  = entryParts[1].replace(/(^\s*)|(\s*$)/g, '');
                if (entryName == 'mapWidth' || entryName == 'buttonText')
                {
                    newWidgetParams[entryName] = entryVal;
                    non_default_params = true;
                }
                else
                    saveWidgetParams[entryName] = entryVal;
            }
        }
        
        apply_visual_params = function ()
        {
            for (cssEntryName in css_list)
                for (cssParamName in css_list[cssEntryName])
            apply(cssEntryName, cssParamName, css_list[cssEntryName][cssParamName]);

            if (newWidgetParams['mapWidth'])
            {
                $('#mapWidth').val(newWidgetParams['mapWidth']);
                apply_map_width(newWidgetParams['mapWidth']);
            }
            if (newWidgetParams['buttonText'])
            {
                button_text = $('#buttonText').val();
                $('#buttonText').val(newWidgetParams['buttonText']);
                apply_search_button_text(newWidgetParams['buttonText']);
            }
            if (saveWidgetParams['header_url'])
            {
                $('#header_url').val(saveWidgetParams['header_url']);
                non_default_params = true;
            }
            if (saveWidgetParams['dateIn'] && saveWidgetParams['dateOut'])
            {
                booking_form_datepicker.val(format_date_readable(saveWidgetParams['dateIn']) + ' — '+ format_date_readable(saveWidgetParams['dateOut']));
                booking_form_datepicker.DatePickerSetDate([saveWidgetParams['dateIn'],saveWidgetParams['dateOut']], true);
            }
            if (saveWidgetParams['rooms'])
                $('#epbs-booking-form .epbs-rooms .epbs-target').val(saveWidgetParams['rooms']);
            if (saveWidgetParams['ppr'])
                $('#epbs-booking-form .epbs-per-room .epbs-target').val(saveWidgetParams['ppr']);
            form_widget_code(false);
        };
          
        attach_color_picker = function ()
        {  
            $('.widget_colorpicker').ColorPicker({
                onChange: function(hsb, hex, rgb, el) {
                    $(el).val('#'+hex);
                    $(el).change();
                },
                onSubmit: function(hsb, hex, rgb, el) {
                    $(el).ColorPickerHide();
                },
                onBeforeShow: function () {
                    $(this).ColorPickerSetColor(this.value);
                }
            })
            .bind('keyup', function(){
                $(this).ColorPickerSetColor(this.value);
            })
            .bind('click', function(){$(this).ColorPickerShow()});
        }
        
        check_default_design = function()
        {
            $("#default_design").change(function() {
              if ($(this).is(":checked")) {
                $(".non-default-design").show();
              } else {
                $(".non-default-design").hide();
              }
            });
            
            if (!savedCssString && !non_default_params)
                $("#default_design").removeAttr('checked');
            $("#default_design").change();
        };
        
    })($142);
    
    function widget_configurator_init()
    {
        previews['en'] = $142('#epbs-booking-form').detach();
        previews['ru'] = $142('#epbs-booking-form').detach();
        previews[language].appendTo('#widget_preview_container');
        $('#widget_preview_container').show();
        init_widget();
        datepickers[language] = booking_form_datepicker;
        restore_visual_params();
        apply_visual_params();
        attach_color_picker();
        check_default_design();
        $('#sendButton').click(function() {form_widget_code(true);});
    }

    $(document).ready(widget_configurator_init);
    //setTimeout(widget_configurator_init, 500);
{/literal}
</script>
{/if}