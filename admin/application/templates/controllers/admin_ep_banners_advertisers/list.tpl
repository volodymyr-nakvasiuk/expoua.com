{strip}
<h4>Рекламодатели</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center" width="30">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" name="id" descr="Id"}
 {include file="common/Lists/headerElementGeneral.tpl" name="name" descr="Название"}
 <TH align="center" colspan="2">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
  <TD><a href="{getUrl controller="admin_ep_banners_companies" action="list" parent=$el.id}">{$el.name}</a></TD>

  {include file="common/Actions/general.tpl" el=$el}
 </TR>
{/foreach}
</TABLE>
<b>Всего записей: </b> {$list.rows}

<br />

<p>Добавляем новую запись</p>

<FORM action="{getUrl add="1" action="insert"}" method="post">
 Название: <INPUT type="text" name="name" size="20" /><br />
 <INPUT type="submit" value="Добавить" />
</FORM>

{/strip}