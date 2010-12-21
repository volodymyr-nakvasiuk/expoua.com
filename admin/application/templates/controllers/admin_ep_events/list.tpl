{strip}
<h4>События</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" name="id" descr="Id"}
 {include file="common/Lists/headerElementCheckbox.tpl" width="30" name="active" descr="A"}
 {include file="common/Lists/headerElementDatesDuo.tpl" width="100" descr="Даты"}
 {include file="common/Lists/headerElementGeneral.tpl" name="number" descr="Номер"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="brands_id" controller="admin_ep_brands" descr="Название бренда"}
 <TH align="center" colspan="3">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
  <TD align="center">
  <FORM method="post" action="{getUrl add="1" controller="admin_ep_events" action="update" id=$el.id}" style="padding:0px; margin:0px;">
  <INPUT type="checkbox" {if $el.active==1} checked{/if} onclick="this.form.submit();">
  <INPUT type="hidden" name="active" value="{if $el.active==1}0{else}1{/if}">
  </FORM>
  </TD>
  <TD align="center"><nobr>{$el.date_from}</nobr><br />{$el.date_to}</TD>
  <TD>{$el.number}</TD>
  <TD>{$el.brand_name}</TD>

  <TD><a href="{getUrl add="1" action="copy" id=$el.id}"><img title="Копировать" src="/images/admin/icons/copy.gif" border="0" width="15"></a></TD>
  {include file="common/Actions/general.tpl" el=$el}
 </TR>
{/foreach}
</TABLE>
<b>Всего записей: </b> {$list.rows}
<p><a href="{getUrl add="1" action="add"}">Добавить новый бренд</a></p>
{/strip}