<h4>Список вопросов рекламодателей</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>

{else}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<table border="0" class="list" width="100%">
<tr>
  {include file="common/Lists/headerElementGeneral.tpl" name="advertiser_name" controller="admin_ep_pblbanners_users" descr="Контакт"}
  {include file="common/Lists/headerElementGeneral.tpl" name="contact_email" controller="admin_ep_partners" descr="E-mail"}
  {include file="common/Lists/headerElementAutocomplete.tpl" name="advertiser_company" controller="admin_ep_pblbanners_users" descr="Рекламодатель"}
  <th>Дата</th>
</tr>
{foreach from=$list.data item="el" name="fe"}
<tr class="{cycle values="odd,even"}">
  <td><a href="{getUrl add="1" action="edit" parent=$el.users_id id=$el.id}"{if empty($el.answer)} style="font-weight:bold;"{/if}>{$el.advertiser_name}</a></td>
  <td><a href="mailto:{$el.email}"{if empty($el.answer)} style="font-weight:bold;"{/if}>{$el.email}</a></td>
  <td><a href="{getUrl add="1" action="edit" parent=$el.users_id id=$el.id}"{if empty($el.answer)} style="font-weight:bold;"{/if}>{$el.advertiser_company}</a></td>
  <td align="center"><span{if empty($el.answer)} style="font-weight:bold;"{/if}>{$el.date_posted}</span></td>
</tr>
{/foreach}
</table>

{/if}
