{include file="common/contentVisualEdit.tpl" textarea="description"}

<SCRIPT type="text/javascript">
objEventsList = Shelby_Backend.ListHelper.cloneObject('objEventsList');
objCitiesList = Shelby_Backend.ListHelper.cloneObject('objCitiesList');

objEventsList.columns = new Array(new Array('active', 'A'), new Array('date_from', 'Дата'), new Array('brand_name', 'Название'));
objEventsList.returnFieldId = 'events_id';
objEventsList.feedUrl = '{getUrl controller="admin_ep_events" action="list" sort="date_from:DESC" feed="json"}';
objEventsList.writeForm();

objCitiesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('country_name', 'Страна'));
objCitiesList.returnFieldId = 'cities_id';
objCitiesList.feedUrl = '{getUrl controller="admin_ep_locations_cities" action="list" feed="json"}';
objCitiesList.writeForm();

{literal}
objEventsList.callbackUser = function(entry) {
	var text = '';

	text = '<div id="companies_to_events' + entry.id + '"><input type="hidden" name="companies_to_events[' + entry.id + '][id]" value="' + entry.id + '">' + entry.brand_name + " (" + entry.date_from + ') Номер стенда: <input type="text" size="10" name="companies_to_events[' + entry.id + '][stand_num]"> <input type="button" value="Удалить" onClick="$(\'#companies_to_events' + entry.id + '\').remove();"></div>';

	$("#events_list").append(text);
}
{/literal}
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="insert"}" enctype="multipart/form-data">

<h4>Добавляем новую компанию</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Категории:</TD>
  <TD>
   {foreach from=$list_categories item="el"}
    <INPUT type="checkbox" name="companies_to_brands_categories[{$el.id}]" value="{$el.id}" /> {$el.name}</BR >
   {/foreach}
  </TD>
 </TR>
 <TR>
  <TD>Локальный язык:</TD>
  <TD>
   <SELECT name="local_languages_id">
   <OPTION value="">(Не указан)</OPTION>
   {foreach from=$list_languages item="el"}<OPTION value="{$el.id}">{$el.name}</OPTION>{/foreach}
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD>Город:</TD>
  <TD><INPUT type="text" size="5" name="cities_id" id="cities_id"> <INPUT type="button" onclick="objCitiesList.showPopUp();" value="Выбрать"> <SPAN id="cities_id_name"></SPAN></TD>
 </TR>
 <TR>
  <TD>Логотип:</TD>
  <TD><INPUT type="file" name="logo"></TD>
 </TR>
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="70" name="name"></TD>
 </TR>
 <TR>
  <TD colspan="2">Описание:<BR />
   <TEXTAREA name="description" id="description" style="width:95%; height:500px;"></TEXTAREA>
  </TD>
 </TR>
 <TR>
  <TD>Адрес:</TD>
  <TD><TEXTAREA name="address" style="width:90%; height:50px;"></TEXTAREA></TD>
 </TR>
 <TR>
  <TD>Индекс:</TD>
  <TD><INPUT type="text" name="postcode" size="25" /></TD>
 </TR>
 <TR>
  <TD>Электронный адрес:</TD>
  <TD><INPUT type="text" name="email" size="25" /></TD>
 </TR>
 <TR>
  <TD>URL:</TD>
  <TD><INPUT type="text" name="web_address" size="25" /></TD>
 </TR>
 <TR>
  <TD>Телефон:</TD>
  <TD><INPUT type="text" name="phone" size="25" /></TD>
 </TR>
 <TR>
  <TD>Факс:</TD>
  <TD><INPUT type="text" name="fax" size="25" /></TD>
 </TR>
 <TR>
  <TD colspan="2">
   Связь с выставками:
   <INPUT type="button" onclick="objEventsList.showPopUp();" value="Добавить"><BR />
   <P id="events_list"></P>
  </TD>
 </TR>
 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Добавить"></TD></TR>
</TABLE>

</FORM>

<p><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></p>