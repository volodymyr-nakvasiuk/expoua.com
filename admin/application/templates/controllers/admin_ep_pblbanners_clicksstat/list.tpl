{strip}
<h4>Статистика кликов по баннерам</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 {include file="common/Lists/headerElementDatesDuo.tpl" width="120" name="date_from" descr="Дата клика"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="banners_id" controller="admin_ep_pblbanners_banners" descr="Баннер"}
 <TH align="center" width="60">Страна</TH>
 <TH align="center" width="60">Цена</TH>
 <TH align="center" width="60">IP</TH>
 </TR>

{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD>{$el.date_click}</TD>
  <TD><a href="{getUrl add=1 del="page" search="banners_id=`$el.banners_id`"}">{$el.banner_name}</a></TD>
  <TD align="center">{$el.country_code}</TD>
  <TD align="center">{$el.price}</TD>
  <TD align="center">{$el.clicker_ip}</TD>
 </TR>
{/foreach}

</TABLE>
<b>Всего записей: </b> {$list.rows}
{/strip}