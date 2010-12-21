{strip}
<h4>Бренд+Событие{if isset($user_params.show) && $user_params.show == "all"} (ВСЕ){/if}</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="id" descr="Id"}
 {include file="common/Lists/headerElementDatesDuo.tpl" width="100" name="date_from" descr="Даты"}
 {include file="common/Lists/headerElementAutocomplete.tpl" align="center" name="organizers_id" controller="sab_jsonfeeds" action="organizers" descr="Организатор"}
 {include file="common/Lists/headerElementAutocomplete.tpl" align="center" name="brands_id" controller="sab_jsonfeeds" action="brands" descr="Бренд"}
 {include file="common/Lists/headerElementAutocomplete.tpl" align="center" name="expocenters_id" controller="sab_jsonfeeds" action="expocenters" descr="Выставочный центр"}
 {include file="common/Lists/headerElementAutocomplete.tpl" align="center" name="countries_id" controller="sab_jsonfeeds" action="countries" descr="Страна"}
 {include file="common/Lists/headerElementAutocomplete.tpl" align="center" name="cities_id" controller="sab_jsonfeeds" action="cities" descr="Город"}
 <TH align="center">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{if isset($list_brands_marked[$el.brands_id])}marked{elseif $el.drafts_brand_cnt > 0}marked_blue{else}{cycle values="odd,even"}{/if}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
  <TD align="center">{$el.date_from}<br />{$el.date_to}</TD>
  <TD align="center"><a href="{getUrl add="1" search="organizers_id=`$el.organizers_id`"}">{$el.organizer_name}</a></TD>
  <TD align="center">{$el.name}</TD>
  <TD align="center">{$el.expocenter_name}</TD>
  <TD align="center">{$el.country_name}</TD>
  <TD align="center">{$el.city_name}</TD>

  <TD align="center"><a href="{getUrl controller="sab_operator_drafts" action="add" id=$el.id}" target="_blank"><img title="Создать черновик на основе этого события" src="/images/admin/icons/copy.gif" border="0" width="16"></a></TD>

 </TR>
{/foreach}
</TABLE>

{/strip}