<h4>База общественных организаций</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="id" descr="Id"}
 {include file="common/Lists/headerElementGeneral.tpl" width="30" align="center" name="active" descr="A"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="name" descr="Название"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="city_name" descr="Город"}
 <TH align="center">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
  <TD align="center"><INPUT type="checkbox" {if $el.active==1} checked="checked"{/if} disabled="disabled"/></TD>
  <TD>{$el.name}</TD>
  <TD align="center">{$el.city_name}</TD>
  <TD width="20" align="center"><a href="{getUrl add="1" action="edit" id=$el.id}"><img alt="{#previewAction#}" src="{$document_root}images/admin/icons/page_text.gif" border="0" width="16"></a></TD>
 </TR>
{/foreach}
</TABLE>
