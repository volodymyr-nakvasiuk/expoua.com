{strip}
<h4>Планы показов</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

<SCRIPT type="text/javascript">
var bp_url = "{getUrl add="1" del="search"}";
{literal}
 function chBPlaceFilter(id) {
 	if (id != "0") {
 		bp_url += "search/" + Shelby_Backend.createSearchParam("places_id", id, "=", true) + "/";
 	}
 	document.location.href = bp_url;
 }
 {/literal}</SCRIPT>

<div>
{assign var="sel_bp_id" value=0}
{foreach from=$list.search item="el"}
 {if $el.column == "places_id"}{assign var="sel_bp_id" value=$el.value}{/if}
{/foreach}
 Фильтр по баннероместу:
 <select onchange="chBPlaceFilter(this.value);">
  <option value="0">(Не выбрано)</option>
  {foreach from=$list_places item="el"}
   <option value="{$el.id}"{if $sel_bp_id == $el.id} selected="selected"{/if}>{$el.name}</option>
  {/foreach}
 </select><br/><br/>
</div>

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" name="id" descr="Id"}
 {include file="common/Lists/headerElementGeneral.tpl" name="name" descr="Название"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="companies_id" controller="admin_ep_banners_companies" descr="Кампании"}
 {include file="common/Lists/headerElementGeneral.tpl" name="date_from" descr="Даты"}
 <TH align="center" colspan="2">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
  <TD>{$el.name}</TD>
  <TD>{$el.company_name}</TD>
  <TD align="center">
   {if !empty($el.date_from)}<nobr>с {$el.date_from}</nobr>{/if}
   {if !empty($el.date_to)} <nobr>по {$el.date_to}</nobr>{/if}
  </TD>

  {include file="common/Actions/general.tpl" el=$el}
 </TR>
{/foreach}
</TABLE>
<b>Всего записей: </b> {$list.rows}

<br />

<p><a href="{getUrl add="1" action="add"}">Добавить новую запись</a></p>

{/strip}