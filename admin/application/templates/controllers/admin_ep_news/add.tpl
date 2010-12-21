{include file="common/contentVisualEdit.tpl" textarea="content"}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>

objBrandsList = Shelby_Backend.ListHelper.cloneObject('objBrandsList');
objOrganizersList = Shelby_Backend.ListHelper.cloneObject('objOrganizersList');
objExpocentersList = Shelby_Backend.ListHelper.cloneObject('objExpocentersList');
objServiceCoList = Shelby_Backend.ListHelper.cloneObject('objServiceCoList');
objParticipantList = Shelby_Backend.ListHelper.cloneObject('objParticipantList');

objBrandsList.columns = new Array(new Array('id', 'Id'), new Array('name', 'Название'), new Array('organizer_name', 'Организатор'));
objBrandsList.returnFieldId = 'brands_id';
objBrandsList.feedUrl = '{getUrl controller="admin_ep_brands" action="list" feed="json"}';
objBrandsList.writeForm();

objOrganizersList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'));
objOrganizersList.returnFieldId = 'organizers_id';
objOrganizersList.feedUrl = '{getUrl controller="admin_ep_organizers" action="list" feed="json"}';
objOrganizersList.writeForm();

objExpocentersList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('city_name', 'Город'));
objExpocentersList.returnFieldId = 'expocenters_id';
objExpocentersList.feedUrl = '{getUrl controller="admin_ep_expocenters" action="list" feed="json"}';
objExpocentersList.writeForm();

objServiceCoList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'));
objServiceCoList.returnFieldId = 'service_companies_id';
objServiceCoList.feedUrl = '{getUrl controller="admin_ep_servicecomp" action="list" feed="json"}';
objServiceCoList.writeForm();

objParticipantList.columns = new Array(new Array('id', 'Id'),  new Array('active', 'A'), new Array('name', 'Заголовок'));
objParticipantList.returnFieldId = 'events_pariticipants_id';
objParticipantList.feedUrl = '{getUrl controller="admin_ep_eventsparticipants" action="list" feed="json"}';
objParticipantList.writeForm();

{literal}
$(document).ready(function() {

	$('#date_public').datepicker();

});
{/literal}
</SCRIPT>

<h4>Добавляем новость</h4>

<FORM method="post" action="{getUrl add="1" action="insert"}">

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Бренд:</TD>
  <TD><INPUT type="text" size="5" name="brands_id" id="brands_id" value=""> <INPUT type="button" onclick="objBrandsList.showPopUp();" value="Выбрать"> <SPAN id="brands_id_name"></SPAN></TD>
 </TR>
 <TR>
  <TD>Категория брендов:</TD>
  <TD>
   <SELECT name="brands_categories_id">
    <OPTION value="">(Не выбрана)</OPTION>
    {foreach from=$list_brand_categories.data item="el"}
     <OPTION value="{$el.id}">{$el.name}</OPTION>
    {/foreach}
   </SELECT>
 </TR>
 <TR>
  <TD>Страна:</TD>
  <TD>
   <SELECT name="countries_id">
    <OPTION value="">(Не выбрана)</OPTION>
    {foreach from=$list_countries.data item="el"}
     <OPTION value="{$el.id}">{$el.name}</OPTION>
    {/foreach}
   </SELECT>
 </TR>
 <TR>
  <TD>Организатор:</TD>
  <TD><INPUT type="text" size="5" name="organizers_id" id="organizers_id" value=""> <INPUT type="button" onclick="objOrganizersList.showPopUp();" value="Выбрать"> <SPAN id="organizers_id_name"></SPAN></TD>
 </TR>
 <TR>
  <TD>Выставочный центр:</TD>
  <TD><INPUT type="text" size="5" name="expocenters_id" id="expocenters_id" value=""> <INPUT type="button" onclick="objExpocentersList.showPopUp();" value="Выбрать"> <SPAN id="expocenters_id_name"></SPAN></TD>
 </TR>
 <TR>
  <TD>Сервисная компания:</TD>
  <TD><INPUT type="text" size="5" name="service_companies_id" id="service_companies_id" value=""> <INPUT type="button" onclick="objServiceCoList.showPopUp();" value="Выбрать"> <SPAN id="service_companies_id_name"></SPAN></TD>
 </TR>
 <TR>
  <TD>Участник выставки:</TD>
  <TD><INPUT type="text" size="5" name="events_pariticipants_id" id="events_pariticipants_id" value=""> <INPUT type="button" onclick="objParticipantList.showPopUp();" value="Выбрать"> <SPAN id="events_pariticipants_id_name"></SPAN></TD>
 </TR>
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="50" name="name" value=""></TD>
 </TR>
 <TR>
  <TD>Дата новости:</TD>
  <TD><INPUT type="text" size="12" name="date_public" id="date_public" value="{$smarty.now|date_format:"%Y-%m-%d"}"></TD>
 </TR>
 <TR>
  <TD colspan="2">Преамбула:<BR />
   <TEXTAREA name="preambula" id="preambula" style="width:95%; height:100px;"></TEXTAREA>
  </TD>
 </TR>
 <TR>
  <TD colspan="2">Полный текст:<BR />
   <TEXTAREA name="content" id="content" style="width:95%; height:500px;"></TEXTAREA>
  </TD>
 </TR>
 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Добавить"></TD></TR>
</TABLE>

</FORM>