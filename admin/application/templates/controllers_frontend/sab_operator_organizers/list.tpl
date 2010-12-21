<h4 style="float:left;">Организаторы</h4>

<div style="float:right;">
{include file="common/Lists/generalFilterDescription.tpl"}
</div>
<div style="clear:both;"></div>

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="id" descr="Id"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="name" descr="Название"}
 {include file="common/Lists/headerElementAutocomplete.tpl" align="center" name="countries_id" controller="sab_jsonfeeds" action="countries" descr="Страна"}
 {include file="common/Lists/headerElementAutocomplete.tpl" align="center" name="cities_id" controller="sab_jsonfeeds" action="cities" descr="Город"}
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
  <TD><a href="{getUrl controller="sab_operator_brands" action="list" search="organizers_id=`$el.id`"}">{$el.name}</a></TD>
  <TD>{$el.country_name}</TD>
  <TD>{$el.city_name}</TD>
 </TR>
{/foreach}
</TABLE>