{strip}
<h4>Статистика показов баннеров</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" name="id" descr="Id"}
 {include file="common/Lists/headerElementGeneral.tpl" name="name" descr="План показа"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="companies_id" controller="admin_ep_banners_companies" descr="Кампания"}
 <TH align="center" width="60">Показов</TH>
 <TH align="center" width="60">Кликов</TH>
 <TH align="center" width="60">CTR</TH>
 <TH align="center" colspan="1">Действия</TH>
</TR>

{assign var="total_shows" value=0}
{assign var="total_clicks" value=0}

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{$el.id}</TD>
  <TD><a href="{getUrl add="1" action="view" id=$el.id}">{$el.name}</a></TD>
  <TD>{$el.company_name}</TD>
  <TD align="center">{$el.shows}</TD>
  <TD align="center">{$el.clicks}</TD>
  <TD align="center">{if $el.clicks==0}0{else}{$el.clicks/$el.shows*100|string_format:"%.4f"}{/if}%</TD>

{assign var="total_shows" value=$total_shows+$el.shows}
{assign var="total_clicks" value=$total_clicks+$el.clicks}

  {include file="common/Actions/general.tpl" el=$el}
 </TR>
{/foreach}
 <TR>
  <TD colspan="3" align="right"><b>Всего по странице:</b> </TD>
  <td align="center"><b>{$total_shows}</b></td>
  <td align="center"><b>{$total_clicks}</b></td>
  <td align="center"><b>{if $total_clicks==0}0{else}{$total_clicks/$total_shows*100|string_format:"%.4f"}{/if}%</b></td>
 </TR>
</TABLE>
<b>Всего записей: </b> {$list.rows}
{/strip}


{if $smarty.get.debug}{debug}{/if}