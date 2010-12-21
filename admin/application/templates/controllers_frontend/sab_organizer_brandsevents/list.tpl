<table cellspacing="0">
<tr>
  <th colspan="4">{#captionLegend#}</th>
</tr>
<tr>
  <td class="blue_hilight">{#exhib_active#}</td>
  <td class="yellow_hilight">{#inprocess_treatment#}</td>
  <td class="red_hilight">{#declined_editor#}</td>
  <td class="grey_hilight">{#exhib_deactivated#}</td>
</tr>
</table>

{if empty($list.data) && empty($list_drafts.data)}
<p>{#records_notpresent#}</p>
{/if}

<table border="0" width="100%">
<tr>
 <th align="center" width="90">{#dates#}</th>
 <th align="center">{#title#}</th>
 <th align="center">{#exhCenter#}</th>
 <th align="center">{#country#}</th>
 <th align="center">{#city#}</th>
 {if $selected_language == 'ru'}<th align="center" style="color:red;">{#captionPremium#}</th>{/if}
 <th align="center" colspan="2">{#actions#}</th>
</tr>

{foreach from=$list_drafts.data item="el" name="fe"}
<tr class="{if $el.status==-1}red_hilight{else}yellow_hilight{/if}">
  <td align="center">
   {$el.date_from}<br />{$el.date_to}
  </td>
  <td>
   {$el.brand_name}
   {if !empty($el.comments)}<img src="/images/admin/icons/comment.gif" title="|{$el.comments|escape:"html"|replace:"\r\n":"|"}" class="tooltip_coment" />{/if}
  </td>
  <td>{$el.expocenter_name}&nbsp;</td>
  <td align="center">{$el.country_name}&nbsp;</td>
  <td align="center">{$el.city_name}&nbsp;</td>
  {if $selected_language == 'ru'}<td></td>{/if}
  <td align="center" width="20">{if $el.status==-1}<a href="{getUrl controller="sab_organizer_drafts" action="edit" id=$el.id}"><img title="{#change_action#}" src="/images/admin/list-edit.gif" border="0" width="23" height="21"></a>{/if}&nbsp;</td>
  <td></td>
</tr>
{/foreach}

{foreach from=$list.data item="el" name="fe"}
 <tr class="{cycle values="odd,even"} {if $el.active != 1}grey_hilight{else}blue_hilight{/if}">
  <td align="center">{$el.date_from}<br />{$el.date_to}</td>
  <td>{$el.name}&nbsp;</td>
  <td>{$el.expocenter_name}&nbsp;</td>
  <td align="center">{$el.country_name}&nbsp;</td>
  <td align="center">{$el.city_name}&nbsp;</td>
  {if $selected_language == 'ru'}<td align="center" style="color:red;">{if $el.premium==1}{#yes#}{else}<a href="{getUrl controller="sab_organizer_premium" action="add" parent=$el.id}" style="color:red;" onclick="NewWindow(this.href,'premium','600','450','yes','center');return false" onfocus="this.blur()">{#orderAction#}</a>{/if}</td>{/if}

  <td align="center" width="20">
   {if $el.active==1}
   <a href="http://www.expopromoter.com/Event/lang/{$selected_language}/id/{$el.id}/" target="_blank"><img title="{#look_event#}" src="/images/admin/list-preview.gif" border="0" height="21" width="23"></a>
   {else}
   &nbsp;
   {/if}
  </td>
  <td align="center"><a href="{getUrl add="1" action="edit" id=$el.id}"><img title="Редактировать событие" src="/images/admin/list-edit.gif" border="0" height="21" width="23"></a>&nbsp;</td>
</tr>
{/foreach}
</table>
