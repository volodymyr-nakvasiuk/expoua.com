<h4>База запросов пользователей</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="id" descr="Id"}
 {include file="common/Lists/headerElementCheckbox.tpl" width="30" align="center" name="viewed" descr="V"}
 {if $user_params.parent == 'serviceCompanyRequest'}
  {include file="common/Lists/headerElementAutocomplete.tpl" align="center" name="parent" descr="Запрос к" controller="admin_ep_servicecomp"}
 {elseif $user_params.parent == 'exhibitionCenterRequest'}
  {include file="common/Lists/headerElementAutocomplete.tpl" align="center" name="parent" descr="Запрос к" controller="admin_ep_expocenters"}
 {else}
  {include file="common/Lists/headerElementAutocomplete.tpl" align="center" name="parent" descr="Запрос к" controller="admin_ep_organizers"}
 {/if}
 <TH align="center">Язык запроса</TH>
 <TH align="center">Дата запроса</TH>
 <TH align="center">Host</TH>
 <TH align="center" colspan="3">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
   <TD align="center"><INPUT type="checkbox" {if $el.viewed==1} checked{/if} disabled></TD>
  <TD>
   {$el.name}
   {if isset($el.brand_name)}<BR />{$el.brand_name} ({$el.country_name}, {$el.city_name} {$el.date_from}){/if}
  </TD>
  <TD align="center">{$el.lang_name}</TD>
  <TD align="center">{$el.date_add}</TD>
  <TD align="center">{$el.ip}</TD>
 {include file="common/Actions/general.tpl" el=$el}
 </TR>
{/foreach}
</TABLE>

<b>Всего записей: </b> {$list.rows}