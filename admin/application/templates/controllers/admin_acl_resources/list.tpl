<h4>Зарегистрированные модули</h4>

{include file="common/Lists/generalFilterDescription.tpl"}

{if empty($list.data)}
<p>Записи отсутсвуют</p>

{else}

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">

{include file="common/Lists/headerElementGeneral.tpl" width="25" align="center" name="id" descr="Id"}
{include file="common/Lists/headerElementCheckbox.tpl" width="50" align="center" name="installed" descr="Уст."}
{include file="common/Lists/headerElementCheckbox.tpl" width="50" align="center" name="super" descr="Админ"}
{include file="common/Lists/headerElementGeneral.tpl" align="center" name="code" stype="~" descr="Код"}
{include file="common/Lists/headerElementGeneral.tpl" align="center" name="description" descr="Описание"}
 <TH align="center" colspan="2">Действия</TH>

{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
 <TD align="center">{$el.id}</TD>
 <TD align="center"><INPUT type="checkbox" disabled{if $el.installed==1} checked{/if}></TD>
 <TD align="center"><INPUT type="checkbox" disabled{if $el.super==1} checked{/if}></TD>
 <TD>{$el.code}</TD>
 <TD>{$el.description}</TD>
 {include file="common/Actions/general.tpl" isFirst=$smarty.foreach.fe.first isLast=$smarty.foreach.fe.last}
 </TR>
{/foreach}
</TABLE>

{/if}

<p>Добавляем новую запись</p>

<FORM action="{getUrl add="1" action="insert"}" method="POST">
<INPUT type="hidden" name="installed" value="1">
 Админ: <INPUT type="checkbox" name="super" value="1"><br />
 Код: <INPUT type="text" size="20" name="code"><br />
 Описание: <BR /><TEXTAREA name="description" cols="30" rows="2"></TEXTAREA><br />
 <INPUT type="submit" value="Добавить">
</FORM>