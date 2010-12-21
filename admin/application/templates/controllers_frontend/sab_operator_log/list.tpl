<h4>Журнал работы операторов</h4>

<SCRIPT type="text/javascript">
{literal}

function setFilter() {
	var baseUrl = '{/literal}{getUrl add=1 del="page,search"}{literal}';
	var search = '';

	if ($('#date_from').val() != "") {
		search = 'date_from>' + $('#date_from').val() + ';';
	}
	if ($('#date_to').val() != "") {
		search += 'date_to<' + $('#date_to').val();
	}

	if (search != "") {
		document.location.href = baseUrl + 'search/' + search + '/';
	}
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
по дату: <INPUT type="text" id="date_to" size="12" /> &nbsp; &nbsp;
<INPUT type="button" value="Установить" onclick="setFilter();" />

{include file="common/generalPaging.tpl" pages=$list.pages page=$list.page}

<TABLE border="0" class="list" align="center">
<TR>
 <TH align="center">Дата</TH>
 <th align="center">Тип</th>
 <th align="center">Id принятой выставки</th>
</TR>

{foreach from=$list.data item="el" name="fe"}
 <TR class="{cycle values="odd,even"}">
  <TD align="center">{$el.date_event}</TD>
  <TD align="center">{$el.type}</TD>
  <TD align="center"><a href="{getUrl controller="sab_operator_brandsevents" action="list" show="all" search="id=`$el.events_id`"}">{$el.events_id}</a></TD>
 </TR>
{/foreach}
</TABLE>
<center>Всего записей: <b>{$list.rows}</b></center>