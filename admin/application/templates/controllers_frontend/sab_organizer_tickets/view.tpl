{if $entry_event}
<br /><br /><br /><br />
{if $entry_buyer}
    <table>
        <tr>
            <th>{#Stake#}</th>
            <td>{$entry_buyer.money}</td>
        </tr>
        <tr>
            <th>{#NotificationMailsCSV#}</th>
            <td>{$entry_buyer.notification_emails_csv|replace:',':'<br />'}</td>
        </tr>
        <tr>
            <th>{#Activity#}<br /><br /></th>
            <td>{if $entry_buyer.active}{#Active#}{else}{#Inactive#}{/if}</td>
        </tr>
    </table>
{else}
<script type="text/javascript">
    var previewUrl = '{$smarty.const.TICKETS_HOST}/{$selected_language}/popup/manage/[ID]/preview/';
    var forms = {ldelim}{rdelim};
    {foreach from=$list_events.data item=e}
        {if $e.bp && $e.bp.id_form}
            forms['{$e.bp.id_form}'] = '{$e.name} ({$e.date_from} - {$e.date_to})';
        {/if}
    {/foreach};
{literal}
    var previewWindowOptions = 'width=1024px, height=800px, status=1, location=1, menubar=1, toolbar=1, scrollbars=1';
    
    var selectedPreset = null;
    
    selectPreset = function(id)
    {
        selectedPreset = id;
    }
    
    showPreset = function ()
    {
        if (selectedPreset)
            window.open(previewUrl.replace('[ID]', selectedPreset), null, previewWindowOptions);
        return false;
    }
    
    showPresets = function ()
    {
        if (selectedPreset)
            window.open(previewUrl.replace('[ID]', encodeURIcomponent($.serialize(forms))), null, previewWindowOptions);
        return false;
    }
    
{/literal}
</script>
    <h2>{#OrderTitle#} {$entry_event.brand_name} ({$entry_event.date_from}&nbsp;-&nbsp;{$entry_event.date_to})</h2>    <br />
    <form method="post" action="{getUrl add='1' action='insert'}">
    <table>
    <tr>
        <td>*&nbsp;{#Stake#}</td>
        <td><input type="text" name="money" value="10"></td>
    </tr>
    <tr>
        <td>*&nbsp;{#Buyers#}</td>
        <td><input type="text" name="buyers" value=""></td>
    </tr>
    <tr>
        <td>*&nbsp;{#MaxBudget#}</td>
        <td><input type="text" name="max_money" value=""></td>
    </tr>
    <tr>
        <td>{#Geography#}</td>
        <td><input type="text" name="geography" value=""></td>
    </tr>
    <tr>
        <td>{#YourContacts#}</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>*&nbsp;{#ContactFullName#}</td>
        <td><input type="text" name="contact_name" value=""></td>
    </tr>
    <tr>
        <td>*&nbsp;{#ContactPhone#}</td>
        <td><input type="text" name="contact_phone" value=""></td>
    </tr>
    <tr>
        <td>*&nbsp;{#ContactEmail#}</td>
        <td><input type="text" name="contact_email" value=""></td>
    </tr>
    <tr>
        <td colspan="2" align="right"><input type="submit" value="{#Order#}"></td>
    </tr>
    </table>
    </form>
{/if}
{else}
{#ListMessage#}
<h3>{#selectEvent#}:</h3>
<table>
    <tr>
        <th>{#Event#}</th>
        <th>{#Dates#}</th>
{*        <th>{#Stake#}</th>
        <th>{#MaxBudgetShort#}</th>*}
        <th>{#Order#}</th>
    </tr>
 {foreach from=$list_events.data item="event"}
    <tr>
        <td>{$event.name|escape:"html"}</td>
        <td>{$event.date_from} - {$event.date_to}</td>
{*        <td>{$event.bp.money}</td>
        <td>{$event.bp.max_money}</td>*}
        <td>
            {if !$event.bp}
            <a href="{getUrl add='1' id=$event.id}"><img src="/images/admin/icons/page_new.gif" border="0"></a>
            {else}
            {if $event.bp.active}
                {#Active#}
            {else}
                {#RequestSent#}
            {/if}
            {/if}
        </td>
    </tr>
 {/foreach}
</table>

{/if}