<h4>Список пользователей админ-панели</h4>

{if empty($list.data)}
<p>Записи отсутсвуют</p>

{else}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE class="list" width="100%">

 {include file="common/Lists/headerElementGeneral.tpl" width="30" name="id" descr="Id"}
 {include file="common/Lists/headerElementCheckbox.tpl" width="30" name="active" descr="A"}
 {include file="common/Lists/headerElementGeneral.tpl" name="login" descr="Логин"}
 {include file="common/Lists/headerElementGeneral.tpl" name="name" descr="Имя"}
 <TH align="center"><a href="{getUrl add="1" sort=$HMixed->getSortOrder('objects_id', $list.sort_by)}">Последний вход</a></TH>
 <TH align="center">Группы</TH>
 <TH align="center" colspan="2">Действия</TH>

{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
 <TD align="center">{$el.id}</TD>
 <TD align="center">
  <FORM method="post" action="{getUrl add="1" action="update" id=$el.id}" style="padding:0px; margin:0px;">
  <INPUT type="checkbox" {if $el.active==1} checked{/if} onclick="this.form.submit();">
  <INPUT type="hidden" name="active" value="{if $el.active==1}0{else}1{/if}">
  </FORM>
 </TD>
 <TD>{$el.login}</TD>
 <TD>{$el.name}</TD>
 <TD>{$el.time_lastlogin}</TD>
 <TD>
  {foreach from=$el.groups item="grp_el" name="fe_grp"}
   {$list_groups[$grp_el].name}{if !$smarty.foreach.fe_grp.last}, {/if}
  {/foreach}
 </TD>
 {include file="common/Actions/general.tpl" isFirst=$smarty.foreach.fe.first isLast=$smarty.foreach.fe.last}
 </TR>
{/foreach}
</TABLE>

{/if}

<p>
Добавляем новую запись
<FORM method="post" action="{getUrl add="1" action="insert"}">
 Логин: <INPUT type="text" size="10" name="login" /><br />
 Имя: <INPUT type="text" size="50" name="name" /><br />
<BR />

<INPUT type="submit" value="Добавить">
</FORM>
</p>