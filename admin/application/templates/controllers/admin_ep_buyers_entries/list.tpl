<h4>Баерки</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center" width="30">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" name="id" descr="Id"}
 {include file="common/Lists/headerElementCheckbox.tpl" width="30" name="active" descr="A"}
 {include file="common/Lists/headerElementAutocomplete.tpl" name="events_id" controller="admin_ep_brandsevents" descr="Событие"}
 {include file="common/Lists/headerElementGeneral.tpl" width="80" name="money" descr="Ставка"}
 <TH align="center" width="80">Депозит</TH>
 <TH align="center" colspan="2"></TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
  <TD align="center">
   <FORM method="post" action="{getUrl add="1" action="update" id=$el.id}" style="padding:0px; margin:0px;">
    <INPUT type="checkbox" {if $el.active==1} checked{/if} onclick="this.form.submit();">
    <INPUT type="hidden" name="active" value="{if $el.active==1}0{else}1{/if}">
   </FORM>
  </TD>
  <TD>{$el.brand_name}</TD>
  <TD align="center">{$el.money}</TD>
  <TD align="center">{if is_null($el.deposit)}0{else}{$el.deposit}{/if}</TD>
  
  {include file="common/Actions/general.tpl" el=$el}
 </TR>
{/foreach}
</TABLE>