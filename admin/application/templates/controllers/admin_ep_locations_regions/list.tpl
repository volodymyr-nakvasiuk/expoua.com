<h4>База регионов/стран/городов: Регионы</h4>

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center" width="50">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="50" align="center" name="id" descr="Id"}
 {include file="common/Lists/headerElementGeneral.tpl" width="50" align="center" name="active" descr="A"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="name" descr="Название"}
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
   <TD align="center">
  <INPUT type="checkbox" {if $el.active==1} checked="checked"{/if} disabled="disabled">
 </TD>
 <TD><a href="{getUrl controller="admin_ep_locations_countries" parent=$el.id action="list"}">{$el.name}</a></TD>
 </TR>
{/foreach}
</TABLE>