<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>
objAdvertisersList = Shelby_Backend.ListHelper.cloneObject('objAdvertisersList');
objAdvertisersList.columns = new Array(new Array('id', 'Id'), new Array('name', 'Название'));
objAdvertisersList.returnFieldId = 'advertisers_id';
objAdvertisersList.feedUrl = '{getUrl controller="admin_ep_banners_advertisers" action="list" feed="json"}';
objAdvertisersList.writeForm();

objEventsList = Shelby_Backend.ListHelper.cloneObject('objEventsList');
objEventsList.columns = new Array(new Array('id', 'Id'), new Array('date_from', 'Дата'), new Array('name', 'Название'), new Array('city_name', 'Город'));
objEventsList.returnFieldId = 'events_id';
objEventsList.feedUrl = '{getUrl controller="admin_ep_brandsevents" action="list" feed="json" sort="date_from:DESC"}';
objEventsList.writeForm();

var banners_types_data = {$HMixed->toJson($list_types)};

var banner_file = '<table border="0" align="center"><tr><td>Файл: </td><td><input type="file" name="file"/></td></tr><tr><td>Alt: </td><td><input type="text" name="file_alt" size="20" /></td></tr></table>';

var banner_text = '<table border="0" align="center"><tr><td>Текст: </td><td><textarea style="width:300px; height:100px;" name="text_content"></textarea></td></tr></table>';

var banner_pline = '<table border="0" align="center"><tr><td>Выставка: </td><td><input type="text" name="pline_events_id" id="events_id" size="5"/> <INPUT type="button" onclick="objEventsList.showPopUp();" value="Выбрать"/> <SPAN id="events_id_name"></SPAN></td></tr></table>';

{literal}

objEventsList.callbackUser = function(json) {
	$("#events_id_name").html(json.date_from + ": " + json.name + " (" + json.country_name + "/" + json.city_name + ")");
}

function changeBannerType() {
	var type_id = $("#types_id").val();

	switch (banners_types_data[type_id].media) {
		case "image":
		case "flash":
			$("#banner_form_holder").html(banner_file);
			break;
		case "text":
			$("#banner_form_holder").html(banner_text);
			break;
		case "pline":
			$("#banner_form_holder").html(banner_pline);
			break;
	}
}

$(document).ready(function(){
	changeBannerType();
});
{/literal}
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="insert"}" enctype="multipart/form-data">

<h4>Добавляем новый баннер</h4>

<TABLE border="0" width="100%">
 <TR>
  <TD>Рекламодатель:</TD>
  <TD><INPUT type="text" size="5" name="advertisers_id" id="advertisers_id"/> <INPUT type="button" onclick="objAdvertisersList.showPopUp();" value="Выбрать"/> <SPAN id="advertisers_id_name"></SPAN></TD>
 </TR>
 <TR>
  <TD>Тип баннера: </TD>
  <TD>
   <SELECT name="types_id" id="types_id" onchange="changeBannerType();">
   {foreach from=$list_types item="el"}
    <OPTION value="{$el.id}" media="{$el.media}">{$el.name}</OPTION>
   {/foreach}
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="80" name="name"></TD>
 </TR>
 <TR>
  <TD>Описание:</TD>
  <TD><TEXTAREA name="description" style="width:95%; height:100px;"></TEXTAREA></TD>
 </TR>
</TABLE>

<div style="padding:15px;" id="banner_form_holder"></div>

<CENTER><INPUT type="submit" value="Добавить"></CENTER>

</FORM>

<P><a href="{getUrl add="1" action="list"}">Вернуться к списку</a></P>