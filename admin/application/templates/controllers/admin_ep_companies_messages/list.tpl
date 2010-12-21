<h4>База сообщений компаний</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center" width="30">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="id" descr="Id"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="companies_id" controller="admin_ep_companies_manage" descr="Компания"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="name" descr="Имя"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="email" descr="Email"}
 <TH align="center">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
  <td>{$el.company_name}&nbsp;</td>
  <TD>{$el.name}</TD>
  <TD>{$el.email}</TD>
 {include file="common/Actions/general.tpl" el=$el}
 </TR>
{/foreach}
</TABLE>
Всего записей: {$list.rows}