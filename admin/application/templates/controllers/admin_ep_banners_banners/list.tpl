{strip}
<h4>Баннера</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>
{/if}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="30" name="id" descr="Id"}
 {include file="common/Lists/headerElementGeneral.tpl" name="name" descr="Название"}
 <th>
  Тип: &nbsp;
  <SELECT name="types_id" onchange="document.location.href = '{getUrl add="1" del="search"}search/types_id=' + this.value + '/';">
   <OPTION value="0">(Не выбрано)</OPTION>
   <OPTION value="1">Изображение 180x300</OPTION>
   <OPTION value="2">Изображение 160x80</OPTION>
   <OPTION value="3">Flash 180x300</OPTION>
   <OPTION value="4">Flash 160x80</OPTION>
   <OPTION value="5">Изображение 300x100</OPTION>
   <OPTION value="6">Flash 300x100</OPTION>
   <OPTION value="7">Приоритетная строка выставок</OPTION>
   <OPTION value="8">Текстовый блок</OPTION>
  </SELECT>
 </th>
 <TH align="center" colspan="2">Действия</TH>
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.id}</TD>
  <TD>{$el.name}</TD>
  <TD>{$el.type_name}</TD>

  {include file="common/Actions/general.tpl" el=$el}
 </TR>
{/foreach}
</TABLE>
<b>Всего записей: </b> {$list.rows}

<br />

<p><a href="{getUrl add="1" action="add"}">Добавить новую запись</a></p>

{/strip}