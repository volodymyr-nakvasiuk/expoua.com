{#EventsListMessage#}
<h3>{#EventList#}:</h3>
<table>
    <tr>
        <th>{#Event#}</th>
        <th>{#Dates#}</th>
        <th>{#Activity#}</th>
        {*<th>{#Visitors#}</th>*}
        <th>{#actions#}</th>
        {*<th>{#Deposit#}</th>*}
    </tr>
 {foreach from=$list_buyerPrograms item="buyerProgram"}
    {assign var="event" value=$buyerProgram.event}
    <tr>
        <td>{$event.name|escape:"html"}</td>
        <td>{$event.date_from} - {$event.date_to}</td>
        <td>{if $buyerProgram.active}{#Active#}{else}{#Inactive#}{/if}</td>
        {*<td align="right">{$buyerProgram.num_registrations}</td>*}
        <td>
            &nbsp;&nbsp;&nbsp;
            {if $buyerProgram.id_form}<a href="#" onclick="window.open('{$smarty.const.TICKETS_HOST}/{$selected_language}/popup/manage/{$buyerProgram.id_form}/', null, 'width=800px, height=700px, status=1, location=1, menubar=1, toolbar=1, scrollbars=1')"><img src="/images/admin/list-edit.gif" border="0"></a>{else}<a href="#" onclick="window.open('{$smarty.const.TICKETS_HOST}/{$selected_language}/popup/create/{$buyerProgram.id}/{$buyerProgram.event.name}%20({$buyerProgram.event.date_from}%20-%20{$buyerProgram.event.date_to})/{$buyerProgram.contact_email|escape:'url'}/{$prev_forms_csv}/', null, 'width=1260px, height=700px, status=1, location=1, menubar=1, toolbar=1, scrollbars=1')"><img src="/images/admin/icons/page_new.gif" border="0"></a>{/if}
            &nbsp;&nbsp;&nbsp;
            {*if $buyerProgram.active}<a href="{getUrl add='1' id=$buyerProgram.events_id}"><img src="/images/admin/list-preview.gif" border="0"></a>{/if*}</td>
        {*<td align="right">{$buyerProgram.deposit}</td>*}
    </tr>
 {/foreach}
</table>