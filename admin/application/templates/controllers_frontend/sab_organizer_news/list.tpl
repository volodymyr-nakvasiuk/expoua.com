<table class="list" width="100%">
<tr>
 <th align="center" width="140">{#date_pub#}</th>
 <th align="center">{#title#}</th>
 <th align="center" colspan="2">{#actions#}</th>
</tr>
{foreach from=$list.data item="el"}
<tr>
  <td>{$el.date_created|date_format:"%D %T"}</td>
  <td>{$el.name}</td>
  <td align="center" width="16">
    <a href="http://www.expopromoter.com/News/lang/{$selected_language}/newsid/{$el.id}/" target="_blank"><img title="{#look_event#}" src="/images/admin/list-preview.gif" border="0" height="21" width="23"></a>
  </td>
</tr>
{foreachelse}
<tr valign="middle">
  <td height="200" align="center">{#msgNoNews#}</td>
</tr>
{/foreach}
</table>
