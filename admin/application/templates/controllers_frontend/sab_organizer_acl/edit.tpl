<h2>{#editUser#}</h2>

<p><A href="{getUrl add="1" action="list"}">{#returnToListUsers#}</A></p>

<form method="post" action="{getUrl add="1" action="update"}">

<table border="0" width="100%" style="border-collapse:collapse;" cellpadding="5">
<tr>
  <td width="200" class="wb">{#login#}:</td>
  <td><input type="text" size="25" value="{$entry.login}" disabled /></td>
</tr>

<tr>
  <td width="200" class="wb">{#pswd#}:</td>
  <td><input type="text" name="passwd" value="{$entry.passwd}" size="25" /></td>
</tr>

<tr>
  <td width="200" class="wb">{#name#}:</td>
  <td><input type="text" name="name_fio" value="{$entry.name_fio}" size="25" /></td>
</tr>

<tr>
  <td width="200" class="wb">{#phone#}:</td>
  <td><input type="text" name="phone" value="{$entry.phone}" size="25" /></td>
</tr>

<tr>
  <td width="200" class="wb">{#email#}:</td>
  <td><input type="text" name="email" value="{$entry.email}" size="50" /></td>
</tr>

<tr>
  <td width="200" class="wb">{#position#}:</td>
  <td><input type="text" name="position" value="{$entry.position}" size="50" /></td>
</tr>

<tr>
  <td width="200" class="wb">{#superUser#}:</td>
  <td><input type="checkbox" name="super" size="25" value="1"{if $entry.super==1} checked="checked"{/if} /></td>
</tr>

<tr>
  <td width="200" class="wb">{#modules#}:</td>
  <td>
    <input type="checkbox" name="acl_events_drafts" value="1"{if isset($entry.acl_list[66])} checked="checked"{/if} /> {#mngExhibitions#}<br />
    <input type="checkbox" name="acl_emails" value="1"{if isset($entry.acl_list[73])} checked="checked"{/if} /> {#addrQO#}<br />
    <input type="checkbox" name="acl_requests" value="1"{if isset($entry.acl_list[89])} checked="checked"{/if} /> {#acl_queries#}<br />
    <input type="checkbox" name="acl_news" value="1"{if isset($entry.acl_list[68])} checked="checked"{/if} /> {#addRibNews#}
  </td>
</tr>

<tr>
  <td valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td class="wb">{#exhibitions#}:</td>
    </tr>
    </table>
    <br /><br />
  </td>

  <td>
  {foreach from=$list_brands item="el"}
    <input type="checkbox" name="brands[{$el.id}]" value="{$el.id}"{if isset($entry.barads_list[$el.id])} checked="checked"{/if} /> {$el.name}<br />
  {/foreach}
  </td>
</tr>
<tr><td colspan="2" align="center"><input type="submit" value=" {#updateAction#} " /></td></tr>
</table>
</form>

<p><a href="{getUrl add="1" action="list"}">{#returnToListUsers#}</a></p>

