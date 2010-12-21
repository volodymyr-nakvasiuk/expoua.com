<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>
<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/jqExtensions/ui.datepicker.js"></SCRIPT>

<SCRIPT>

objBrandsEventsList = Shelby_Backend.ListHelper.cloneObject('objBrandsEventsList');
objServiceCoList = Shelby_Backend.ListHelper.cloneObject('objServiceCoList');
objParticipantList = Shelby_Backend.ListHelper.cloneObject('objParticipantList');

objBrandsEventsList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название бренда'), new Array('date_from', 'С даты'));
objBrandsEventsList.returnFieldId = 'events_id';
objBrandsEventsList.feedUrl = '{getUrl controller="admin_ep_brandsevents" action="list" feed="json"}';
objBrandsEventsList.writeForm();

objServiceCoList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'));
objServiceCoList.returnFieldId = 'service_companies_id';
objServiceCoList.feedUrl = '{getUrl controller="admin_ep_servicecomp" action="list" feed="json"}';
objServiceCoList.writeForm();

objParticipantList.columns = new Array(new Array('id', 'Id'), new Array('name', 'Название'));
objParticipantList.returnFieldId = 'events_participants_id';
objParticipantList.feedUrl = '{getUrl controller="admin_ep_eventsparticipants" action="list" feed="json"}';
objParticipantList.writeForm();

{literal}
$(document).ready(function() {

	$('#period_date_from').datepicker();
	$('#period_date_to').datepicker();

});
{/literal}
</SCRIPT>

<h4>Добавление нового пользователя</h4>

<FORM method="post" action="{getUrl add="1" action="update"}">

<TABLE border="1" style="border-collapse:collapse" width="100%">
 <tr>
  <TD width="150">Тип:</TD>
  <TD>
   <SELECT name="type">
    <OPTION value="participant"{if $entry.type=="participant"} selected{/if}>Участник</OPTION>
    <OPTION value="tour"{if $entry.type=="tour"} selected{/if}>Туркомпания</OPTION>
    <OPTION value="ad"{if $entry.type=="ad"} selected{/if}>Объявления</OPTION>
   </SELECT>
  </TD>
 </tr>
 <TR>
  <TD>Сервисная компания:</TD>
  <TD><INPUT type="text" name="service_companies_id" id="service_companies_id" value="{$entry.service_companies_id}" size="5"> <INPUT type="button" value="Выбрать" onclick="objServiceCoList.showPopUp();"></TD>
 </TR>
 <TR>
  <TD>Событие:</TD>
  <TD><INPUT type="text" name="events_id" id="events_id" value="{$entry.events_id}" size="5"> <INPUT type="button" value="Выбрать" onclick="objBrandsEventsList.showPopUp();"></TD>
 </TR>
 <TR>
  <TD>Участник:</TD>
  <TD><INPUT type="text" name="events_participants_id" id="events_participants_id" value="{$entry.events_participants_id}" size="5"> <INPUT type="button" value="Выбрать" onclick="objParticipantList.showPopUp();"></TD>
 </TR>
 <TR>
  <TD>Дата с:</TD>
  <TD><INPUT type="text" name="period_date_from" id="period_date_from" value="{$entry.period_date_from}" size="12"></TD>
 </TR>
 <TR>
  <TD>Дата по:</TD>
  <TD><INPUT type="text" name="period_date_to" id="period_date_to" value="{$entry.period_date_to}" size="12"></TD>
 </TR>
 <TR>
  <TD>Логин:</TD>
  <TD><INPUT type="text" name="login" value="{$entry.login}" size="20"></TD>
 </TR>
 <TR>
  <TD>Пароль:</TD>
  <TD><INPUT type="text" name="password" value="{$entry.password}" size="20"></TD>
 </TR>
 <TR><TD colspan="2" align="center"><INPUT type="submit" value="Изменить"></TD></TR>
</TABLE>

<P><a href="{getUrl add="1" action="list"}">Вернуться к списку</a></P>

</FORM>