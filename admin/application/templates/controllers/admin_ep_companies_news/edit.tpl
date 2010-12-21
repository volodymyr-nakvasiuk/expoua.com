{include file="common/contentVisualEdit.tpl" textarea="content"}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>

objCompaniesList = Shelby_Backend.ListHelper.cloneObject('objCompaniesList');
objCountriesList = Shelby_Backend.ListHelper.cloneObject('objCountriesList');
objEventsList = Shelby_Backend.ListHelper.cloneObject('objEventsList');

objCompaniesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'));
objCompaniesList.returnFieldId = 'companies_id';
objCompaniesList.feedUrl = '{getUrl controller="admin_ep_companies_manage" action="list" feed="json"}';
objCompaniesList.writeForm();

objCountriesList.columns = new Array(new Array('name', 'Страна'));
objCountriesList.returnFieldId = 'countries_id';
objCountriesList.feedUrl = '{getUrl controller="admin_ep_locations_countries" action="list" feed="json"}';
objCountriesList.writeForm();

objEventsList.columns = new Array(new Array('active', 'A'), new Array('date_from', 'Дата'), new Array('brand_name', 'Название'));
objEventsList.returnFieldId = 'events_id';
objEventsList.feedUrl = '{getUrl controller="admin_ep_events" action="list" feed="json"}';
objEventsList.writeForm();

{literal}
objEventsList.callbackUser = function(entry) {
	$("#events_id_name").html(entry.brand_name + " (" + entry.date_from + ")");
}

$(document).ready(function() {
	$('#date_public').datepicker();
});
{/literal}
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="update"}">

<h4>Редактируем новость компании</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="70" name="name" value="{$entry.name}"></TD>
 </TR>
 <TR>
  <TD>Компания:</TD>
  <TD><INPUT type="text" size="5" name="companies_id" id="companies_id" value="{$entry.companies_id}"> <INPUT type="button" onclick="objCompaniesList.showPopUp();" value="Выбрать"> <SPAN id="companies_id_name">{$entry.company_name}</SPAN></TD>
 </TR>
 <TR>
  <TD>Страна:</TD>
  <TD><INPUT type="text" size="5" name="countries_id" id="countries_id" value="{$entry.countries_id}"> <INPUT type="button" onclick="objCountriesList.showPopUp();" value="Выбрать"> <SPAN id="countries_id_name">{$entry.country_name}</SPAN></TD>
 </TR>
 <TR>
  <TD>Выставка:</TD>
  <TD><INPUT type="text" size="5" name="events_id" id="events_id" value="{$entry.events_id}"> <INPUT type="button" onclick="objEventsList.showPopUp();" value="Выбрать"> <SPAN id="events_id_name">{$entry.brand_name}</SPAN></TD>
 </TR>
 <TR>
  <TD>Дата публикации:</TD>
  <TD><INPUT type="text" size="12" name="date_public" id="date_public" value="{$entry.date_public|date_format:"%Y-%m-%d"}"></TD>
 </TR>
 <TR>
  <TD colspan="2">Описание:<BR />
   <TEXTAREA name="content" id="content" style="width:95%; height:500px;">{$entry.content}</TEXTAREA>

  </TD>
 </TR>
 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Добавить"></TD></TR>
</TABLE>

</FORM>

<p><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></p>