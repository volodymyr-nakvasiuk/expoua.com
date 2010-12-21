<h4>Список вопросов организаторов и операторов</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>

{else}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" class="list" width="100%">

 {include file="common/Lists/headerElementGeneral.tpl" width="30" name="id" descr="Id"}
 <TH>Пользователь</TH>
 {include file="common/Lists/headerElementAutocomplete.tpl" name="organizers_id" controller="admin_ep_organizers" descr="Организатор"}
 <TH>Дата</TH>

{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
 <TD align="center">{$el.id}</TD>
 <TD><a href="{getUrl add="1" action="edit" parent=$el.users_operators_id id=$el.id}"{if empty($el.answer)} style="font-weight:bold;"{/if}>{$el.login}</a></TD>
 <TD>{$el.organizer_name}</TD>
 <TD align="center">{$el.date_posted}</TD>
 </TR>
{/foreach}
</TABLE>

{/if}