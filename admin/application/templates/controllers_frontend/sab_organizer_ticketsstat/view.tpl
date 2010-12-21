{#RegistrationMessage#}
{if $entry_event}
<h3>{$entry_event.brand_name} ({$entry_event.date_from} - {$entry_event.date_to})</h3>
{/if}
<table>
    {foreach from=$ticket.namedValues item=value key=name}
    <tr>
        <td>{$name}</td>
        <td>{$value}</td>
    </tr>
    {/foreach}
    {if $ticket.reason}
        <tr>
            <td>{#Status#}</td>
            <td>
                {if $ticket.approve_status>0}
                    {#DenialRequestApproved#}
                {elseif $ticket.approve_status<0}
                    {#DenialRequestDeclined#}
                {else}
                    {#DenialRequestNew#}
                {/if}

            </td>
        </tr>
        <tr>
            <td>{#YourDenialReason#}</td>
            <td>{$ticket.reason}</td>
        </tr>
    {/if}
</table>
{if !$ticket.reason}
<br />
<input type="button" value="{#denyActionTitle#}" onClick="deny({$ticket.id_registration})">
{/if}
<br />
<br />
<a href="{getUrl add='1' del='registrationId' action='list'}">{#backToList#}</a>

{include file="controllers_frontend/`$user_params.controller`/denyFunction.tpl"}