<h4>Журнал работы пользователей</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{include file="common/generalPaging.tpl" pages=500 page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="70" align="center" name="date_event" descr="Дата"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="acl_resources_id" descr="Модуль"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="acl_resources_actions_id" descr="Действие"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="param_id" descr="Параметр"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="acl_admin_users_id" descr="Админ"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="users_operators" descr="Оператор"}
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.date_event}</TD>
  <TD>{$el.code_resource}</TD>
  <TD>{$el.code_action}</TD>
  <TD align="center">{$el.param_id}</TD>
  <TD align="center">{$el.admin_login}</TD>
  <TD align="center">{$el.operator_login}</TD>
 </TR>
{/foreach}
</TABLE>
<b>Всего записей: </b> {$list.rows}