{*include file="controllers_frontend/sab_organizer_requests/top_menu.tpl"*}

<form method="post" action="{getUrl add="1" action="update" id="0"}">

<table border="0" width="100%" class="list" align="center">
<tr>
  <th align="left" width="70%">{#exhibition#}</th>
  <th align="center" width="30%">E-mail</th>
  <th align="right" width="100">{#actions#}</th>
</tr>
{foreach from=$list.data item="el"}
<tr class="{cycle values="odd,even"}">
  <td>{$el.name}</td>
  <td>
    {$el.email_requests|default:'&mdash;'}
    {* <input type="text" size="25" name="email_requests[{$el.id}]" value="{$el.email_requests}" style="width:90%;" /> *}
  </td>
  <td style="text-align:right;">
    <input type="button" value=" {#editAction#} " onclick="location.href='{if $el.events_id}{getUrl controller='sab_organizer_brandsevents' action='edit' id=$el.events_id}{else}{getUrl controller='sab_organizer_drafts' action='add'}{/if}';" />
  </td>
</tr>
{/foreach}
</table>

{* <p><input type="submit" value="{#updateAction#}" /></p> *}

</form>