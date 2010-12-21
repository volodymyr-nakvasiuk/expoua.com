{strip}
<h4>Бренды</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" name="id" descr="Id"}
 {include file="common/Lists/headerElementGeneral.tpl" name="name" descr="Название бренда"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="organizers_id" controller="admin_ep_organizers" descr="Организатор"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="countries_id" controller="admin_ep_locations_countries" descr="Страна"}
 <TH align="center" colspan="3">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{if $el.dead==1}marked_blue{else}{cycle values="odd,even"}{/if}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
  <TD><a href="{getUrl controller="admin_ep_brandsevents" action="list" search="brands_id=`$el.id`"}">{$el.name}</a></TD>
  <TD>{$el.organizer_name}</TD>
  <TD>{$el.country_name}</TD>

  {include file="common/Actions/general.tpl" el=$el}
 </TR>
{/foreach}
</TABLE>
<b>Всего записей: </b> {$list.rows}
<p><a href="{getUrl add="1" action="add"}">Добавить новый бренд</a></p>
{/strip}