<h4>Управление доступом для размещения объявлений</h4>

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="id" descr="Id"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="type" descr="Тип"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="events_id" descr="Id События"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="period_date_from" descr="Дата с"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="period_date_to" descr="Дата по"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="login" descr="Логин"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="brand_name" descr="Бренд"}
 <TH align="center" colspan="3">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
  <TD align="center">{$el.type}</TD>
  <TD align="center">{$el.events_id}</TD>
  <TD align="center">{$el.period_date_from}</TD>
  <TD align="center">{$el.period_date_to}</TD>
  <TD align="center">{$el.login}</TD>
  <TD align="center">{$el.brand_name}</TD>
 {include file="common/Actions/general.tpl" el=$el}
 </TR>
{/foreach}
</TABLE>

<p><a href="{getUrl add='1' action='add' del='page'}">Добавить новую запись</a></p>