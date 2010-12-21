<h4>Журнал работы операторов</h4>

<SCRIPT type="text/javascript">
{literal}

function setFilter() {
	var baseUrl = '{/literal}{getUrl controller="admin_system_logoperators" action="list"}{literal}';
	var search = '';

	search = 'date_from>' + $('#date_from').val() + ';date_to<' + $('#date_to').val() + ';users_operators_id=' + $('#operator').val() + ';type=' + $('#type').val();

	document.location.href = baseUrl + 'search/' + search + '/';
}

$(document).ready(function(){
	$('#date_from').datepicker();
	$('#date_to').datepicker();
});
{/literal}
</SCRIPT>

{include file="common/Lists/generalFilterDescription.tpl"}

<b>Фильтр:</b><br />
с даты: <INPUT type="text" id="date_from" size="12" />
по дату: <INPUT type="text" id="date_to" size="12" /><br />
оператор:
<SELECT id="operator">
{foreach from=$list_operators item="el"}
 <OPTION value="{$el.id}">{$el.login}</OPTION>
{/foreach}
</SELECT>
тип:
<SELECT id="type">
 <OPTION value="accept">Принятно по стандартной цене</OPTION>
 <OPTION value="accept_high">Принято по повышенной цене</OPTION>
 <OPTION value="cancel">Отменено</OPTION>
</SELECT><br />
<INPUT type="button" value="Установить" onclick="setFilter();" />

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" width="100%" class="list">
<TR>
 <TH align="center">N</TH>
 {include file="common/Lists/headerElementGeneral.tpl" width="130" align="center" name="date_event" descr="Дата"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="type" descr="Тип"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="events_id" descr="Id выставки"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="admin_login" descr="Админ"}
 {include file="common/Lists/headerElementGeneral.tpl" align="center" name="operator_login" descr="Оператор"}
</TR>

{assign var="npp_base" value=$HMixed->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')}
{assign var="npp_base" value=`$npp_base*$list.page-$npp_base`}
{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{assign var="npp" value="`$smarty.foreach.fe.iteration+$npp_base`"}{$npp}</TD>
  <TD align="center">{$el.date_event}</TD>
  <TD align="center">{$el.type}</TD>
  <TD align="center">{$el.events_id}</TD>
  <TD align="center">{$el.admin_login}</TD>
  <TD align="center">{$el.operator_login}</TD>
 </TR>
{/foreach}
</TABLE>
<b>Всего записей: </b> {$list.rows}