<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

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

</SCRIPT>

<h4>Редактируем данные об участнике выставки</h4>

<FORM method="post" action="{getUrl add="1" action="update"}">

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD width="70">Выставка: </TD>
  <TD>Id: <INPUT type="text" name="events_id" id="events_id" value="{$entry.events_id}" size="5"> <INPUT type="button" onclick="objBrandsEventsList.showPopUp();" value="Выбрать"> <span id="events_id_name">{$entry.brand_name} (с {$entry.event_date_from} по {$entry.event_date_to}) </span></TD>
 </TR>
 <TR>
  <TD>Сервисник:</TD>
  <TD>Id: <INPUT type="text" name="service_companies_id" id="service_companies_id" value="{$entry.service_companies_id}" size="5"> <INPUT type="button" onclick="objServiceCoList.showPopUp();" value="Выбрать"> <span id="service_companies_id_name">{$entry.service_company_name}</span></TD>
 </TR>
 <TR>
  <TD>Участник:</TD>
  <TD>Id: <INPUT type="text" name="events_participants_id" id="events_participants_id" value="{$entry.events_participants_id}" size="5"> <INPUT type="button" onclick="objParticipantList.showPopUp();" value="Выбрать"> <span id="events_participants_id_name">{$entry.event_participant_name}</span></TD>
 </TR>
 <TR>
  <TD>Email:</TD>
  <TD><INPUT type="text" name="email" value="{$entry.email}"></TD>
 </TR>
 <TR>
  <TD>PIN:</TD>
  <TD>{$entry.pin}</TD>
 </TR>
 <TR>
  <TD>Название: </TD>
  <TD><INPUT type="text" size="20" name="name" value="{$entry.name}"></TD>
 </TR>
 <TR>
  <TD>Тип:</TD>
  <TD>
   <SELECT name="type">
    <OPTION value="participant" {if $entry.type=="participant"}selected{/if}>Участник</OPTION>
    <OPTION value="tour" {if $entry.type=="tour"}selected{/if}>Тур</OPTION>
    <OPTION value="ad" {if $entry.type=="ad"}selected{/if}>Объявление</OPTION>
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD colspan="2">
  Краткое описание:<br />
  <TEXTAREA style="width:95%; height:200px;" name="description_short">{$entry.description_short|escape}</TEXTAREA>
  </TD>
 </TR>
 <TR>
  <TD colspan="2">
  Полное описание:<br />
  <TEXTAREA style="width:95%; height:150px;" name="description">{$entry.description|escape}</TEXTAREA>
  </TD>
 </TR>
</TABLE>

<CENTER><INPUT type="submit" value="Обновить"></CENTER>

</FORM>