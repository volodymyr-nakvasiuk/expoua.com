{* <script type="text/javascript" src="{$document_root}js/jqExtensions/jquery.dimensions.js"></script> *}
{* <script type="text/javascript" src="{$document_root}js/jqExtensions/jquery.cluetip.js"></script> *}

{if empty($list.data) && empty($list_drafts.data)}
<p>{#records_notpresent#}</p>
{/if}

<table border="0" width="100%" class="list">
<tr>
 <th align="center" width="90">{#dates#}</th>
 <th align="center">{#title#}</th>
 <th align="center">{#exhCenter#}</th>
 <th align="center">{#country#}</th>
 <th align="center">{#city#}</th>
 <th align="center">{#pictures#}</th>
 <th align="center">{#actions#}</th>
</tr>

{foreach from=$list.data item="el" name="fe"}
<tr class="{cycle values="odd,even"}">
  <td align="center">{$el.date_from}<br />{$el.date_to}</td>
  <td>{$el.name}&nbsp;</td>
  <td>{$el.expocenter_name}&nbsp;</td>
  <td align="center">{$el.country_name}&nbsp;</td>
  <td align="center">{$el.city_name}&nbsp;</td>
  <td align="center">{$el.galleries}&nbsp;</td>
  <td align="center"><a href="{getUrl add="1" action="list" parent=$el.id}"><img title="{#captionEdit#}" src="/images/admin/list-edit.gif" border="0" height="21" width="23"></a>&nbsp;</td>
</tr>
{/foreach}
</table>

