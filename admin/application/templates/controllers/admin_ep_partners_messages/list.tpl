<h4>Список вопросов веб-партнеров</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>

{else}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<table border="0" class="list" width="100%">
<tr>
  {include file="common/Lists/headerElementGeneral.tpl" name="contact_name" controller="admin_ep_partners" descr="Контакт"}
  {include file="common/Lists/headerElementGeneral.tpl" name="contact_email" controller="admin_ep_partners" descr="E-mail"}
  {include file="common/Lists/headerElementAutocomplete.tpl" name="partners_id" controller="admin_ep_partners" descr="Веб-партнер"}
  <th>Дата</th>
</tr>
{foreach from=$list.data item="el" name="fe"}
<tr class="{cycle values="odd,even"}">
  <td><a href="{getUrl add="1" action="edit" parent=$el.partners_id id=$el.id}"{if empty($el.answer)} style="font-weight:bold;"{/if}>{$el.contact_name}</a></td>
  <td><a href="mailto:{$el.contact_email}"{if empty($el.answer)} style="font-weight:bold;"{/if}>{$el.contact_email}</a></td>
  <td><a href="{getUrl add="1" action="edit" parent=$el.partners_id id=$el.id}"{if empty($el.answer)} style="font-weight:bold;"{/if}>{$el.name}</a></td>
  <td align="center"><span{if empty($el.answer)} style="font-weight:bold;"{/if}>{$el.date_posted}</span></td>
</tr>
{/foreach}
</table>

{/if}
