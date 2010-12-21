<h4>Управление конфигурационными константами</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>

{else}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE width="100%" class="list">

{include file="common/Lists/headerElementGeneral.tpl" width="25" align="center" name="id" descr="Id"}
{include file="common/Lists/headerElementGeneral.tpl" width="150" align="center" name="code" descr="Код"}
{include file="common/Lists/headerElementGeneral.tpl" width="50" align="center" name="value" descr="Значение"}
{include file="common/Lists/headerElementGeneral.tpl" align="center" name="description" descr="Описание"}
<TH align="center" colspan="2">Действия</TH>

{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
 <TD align="center">{$el.id}</TD>
 <TD>{$el.code}</TD>
 <TD align="center">{$el.value}</TD>
 <TD>{$el.description}</TD>
 {include file="common/Actions/general.tpl" isFirst=$smarty.foreach.fe.first isLast=$smarty.foreach.fe.last}
 </TR>
{/foreach}
</TABLE>

{/if}

<p>Добавляем новую запись</p>

<FORM action="{getUrl add="1" action="insert"}" method="POST">
 <TABLE>
  <TR><TD>Код: </TD><TD><INPUT type="text" name="code" size="20" /></TD></TR>
  <TR><TD>Значение: </TD><TD><INPUT type="text" name="value"size="20" /></TD></TR>
  <TR><TD>Описание: </TD><TD><INPUT type="text" name="description" size="50" /></TD></TR>
 </TABLE>

 <INPUT type="submit" value="Добавить">
</FORM>