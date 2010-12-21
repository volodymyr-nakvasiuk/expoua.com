<h4>Группы пользователей</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>

{else}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" class="list" width="100%">

 {include file="common/Lists/headerElementGeneral.tpl" width="30" name="id" descr="Id"}
 {include file="common/Lists/headerElementGeneral.tpl" name="name" descr="Имя"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="parent_group_id" controller="admin_acl_admins_groups" descr="Родительская группа"}
 {include file="common/Lists/headerElementGeneral.tpl" width="30" name="description" descr="Описание"}
 <TH align="center" colspan="2">Действия</TH>

{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
 <TD align="center">{$el.id}</TD>
 <TD>{$el.name}</TD>
 <TD align="center">{if !empty($el.parent_group_id)}{$list.data[$el.parent_group_id].name}{else}(не установлена){/if}</TD>
 <TD>{$el.description}</TD>
 {include file="common/Actions/general.tpl" isFirst=$smarty.foreach.fe.first isLast=$smarty.foreach.fe.last}
 </TR>
{/foreach}
</TABLE>

{/if}

<p>Добавляем новую запись</p>

<FORM action="{getUrl add="1" action="insert"}" method="POST">
 Название: <INPUT type="text" name="name"><br />
 Родительская группа:
  <SELECT name="parent_group_id">
   <OPTION value="">(не установлена)</OPTION>
   {foreach from=$list.data item="el"}
    <OPTION value="{$el.id}">{$el.name}</OPTION>
   {/foreach}
  </SELECT><BR />
 Описание: <BR /><TEXTAREA name="description" cols="50" rows="5"></TEXTAREA><br />
 <INPUT type="submit" value="Добавить">
</FORM>