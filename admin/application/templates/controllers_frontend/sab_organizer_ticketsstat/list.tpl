{if $entry_event}
{#RegistrationsListMessage#}<br /><br />
<h3>{$entry_event.brand_name} ({$entry_event.date_from} - {$entry_event.date_to})</h3>
<div style="width: 100%; text-align: right; margin-bottom: 7px;"><a href="{getUrl add="1" feed='csv' del='page' action='list'}">{#getCSV#}</a></div>
<table>
    <tr>
        <th>{#RegDate#}</th>
        {foreach from=$list_fields item="field"}
            <th>{$field.name}</th>
        {/foreach}
        <th>{#ViewAction#}</th>
        <th>{#RequestDenialAction#}</th>
        <th>{#DenialStatus#}</th>
    </tr>
{foreach from=$tickets.list item="ticket"}
    <tr>
        <td>{$ticket.date}</td>
        {foreach from=$list_fields item="field"}
            <td>{$ticket[$field.type]}</td>
        {/foreach}
        <td><a href="{getUrl add="1" registrationId=$ticket.id_registration action='view'}"><img src="/images/admin/list-preview.gif" border="0"></a></td>
        <td>{if !$ticket.reason}<a href="#" onClick="deny({$ticket.id_registration})">{#denyActionTitle#}</a>{else}&nbsp;{/if}</td>
        <td>
            {if $ticket.reason}
                {if $ticket.approve_status>0}
                    {#DenialRequestApproved#}
                {elseif $ticket.approve_status<0}
                    {#DenialRequestDeclined#}
                {else}
                    {#DenialRequestNew#}
                {/if}
            {else}
                &nbsp;
            {/if}
        </td>
    </tr>
{/foreach}
</table>

{include file="common/generalPaging.tpl" pages=$tickets.pages page=$tickets.page}

<a href="{getUrl add='1' del='registrationId,id' action='list'}">{#backToBuyerProgramsList#}</a>

{include file="controllers_frontend/`$user_params.controller`/denyFunction.tpl"}
{else}

{#CommonStatsMessage#}<br /><br />
{*<h3>{#EventList#}:</h3>*}
<table>
    <tr>
        <th>{#Event#}</th>
        <th>{#Dates#}</th>
        <th>{#Activity#}</th>
        <th>{#BuyersTotal#}</th>
        <th>{#BuyersDeclined#}</th>
        <th>{#BuyersAccepted#}</th>
        <th>{#BuyerCost#}</th>
        <th>{#TotalAcceptedCost#}</th>
        <th>{#MaxBudget#}</th>
        {*<th>{#actions#}</th>*}
    </tr>
 {foreach from=$list_buyerPrograms item="buyerProgram"}
    {assign var="event" value=$buyerProgram.event}
    <tr>
        <td>{if $buyerProgram.active}<a href="{getUrl add='1' id=$buyerProgram.events_id}">{$event.name|escape:"html"}</a>{else}{$event.name|escape:"html"}{/if}</td>
        <td>{$event.date_from}&nbsp;- {$event.date_to}</td>
        <td>{if $buyerProgram.active}{#Active#}{else}{#Inactive#}{/if}</td>
        <td align="right">{$buyerProgram.num_registrations}</td>
            {assign var="num_accepted" value=$buyerProgram.num_registrations-$buyerProgram.num_declined}
        <td align="right">{$buyerProgram.num_declined}</td>
        <td align="right">{$num_accepted}</td>
        <td align="right">{$buyerProgram.money}</td>
        <td align="right">{$buyerProgram.money*$num_accepted|string_format:"%.2f"}</td>
        <td>{$buyerProgram.max_money}</td>
        {*<td>
            &nbsp;&nbsp;&nbsp;
            {if $buyerProgram.active}<a href="{getUrl add='1' id=$buyerProgram.events_id}"><img src="/images/admin/list-preview.gif" border="0"></a>{/if}</td>*}
    </tr>
 {/foreach}
</table>
{/if}