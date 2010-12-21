{include file="common/contentVisualEdit.tpl" textarea="thematic_sections,description" imagesDefaultParent="events"}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>
<script type="text/javascript" language="javascript" src="{$document_root}js/adminFormValidator.js"></script>

<SCRIPT type="text/javascript" language="javascript">

objBrandsList = Shelby_Backend.ListHelper.cloneObject('objBrandsList');
objCitiesList = Shelby_Backend.ListHelper.cloneObject('objCitiesList');
objExpocentersList = Shelby_Backend.ListHelper.cloneObject('objExpocentersList');

objBrandsList.columns = new Array(new Array('id', 'Id'), new Array('name', 'Название'), new Array('organizer_name', 'Организатор'));
objBrandsList.returnFieldId = 'brands_id';
objBrandsList.feedUrl = '{getUrl controller="admin_ep_brands" action="list" feed="json"}';
objBrandsList.writeForm();

objCitiesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('country_name', 'Страна'));
objCitiesList.returnFieldId = 'cities_id';
objCitiesList.feedUrl = '{getUrl controller="admin_ep_locations_cities" action="list" feed="json"}';
objCitiesList.writeForm();

formVal = Shelby_Backend.FormValidator.cloneObject("formVal");
formVal.headerMessage = "{#msgThereAreErrors#}:\n";

formVal.addField("brands_id", "num", 1, "- Выберите бренд\n");
formVal.addField("cities_id", "num", 1, "- {#msgChooseCity#}\n");
{if $selected_language=='en'}{literal}
formVal.userValidation = function () {
	tinyMCE.triggerSave(false, true);
	var length = $('<span/>').html($('#description').val()).text().length;
	if ( length < 300 ) {
		location = '#anchor_description';
		tinyMCE.execCommand('mceFocus', false, 'description');
		return '- Описание выставки должно быть минимум 300 символов.\nСейчас введено ' + length + ' символ(ов).\n';
	}
	return true;
}
{/literal}{/if}{literal}

objBrandsList.callbackUser = function(entry) {
	var brand_entry = new Object();
	var brand_url = '{/literal}{getUrl controller="admin_ep_brands" action="view" feed="json"}{literal}id/' + entry.id + '/';
	var organizer_url = '{/literal}{getUrl controller="admin_ep_organizers" action="view" feed="json"}{literal}id/';

	$.getJSON(brand_url, function(json) {
		organizer_url += json.entry.organizers_id + "/";

		$("#brands_id_name").html(json.entry.name + "<BR />" + json.entry.name_extended);

		$.getJSON(organizer_url, function(json) {
			//alert(json.entry.name);
			$("#email").val(json.entry.email);
			$("#web_address").val(json.entry.web_address);
			$("#phone").val(json.entry.phone);
			$("#fax").val(json.entry.fax);
		});

	});
}

objCitiesList.callbackUser = function(entry) {
	createExpoCentersList(entry.id);
}

function createExpoCentersList(id) {
	var url = '{/literal}{getUrl controller="admin_ep_expocenters" results="999" action="list" feed="json" sort="name:ASC"}{literal}search/cities_id:' + id + '/';
	var exCenter = $("#expocenters_id");

	exCenter.html('<option value="">(Не установлено)</option>');

	$.getJSON(url, function(json) {
		$.each(json.list.data, function(i) {
			var tmp = $("<option></option>")
				.attr("value", json.list.data[i].id)
				.text(json.list.data[i].name);

			tmp.appendTo(exCenter);
		});

		$("#expocenters_id option:first").attr("selected","selected");
	});

}

$(document).ready(function(){
	$('#date_from').datepicker();
	$('#date_to').datepicker();
});
{/literal}
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="insert"}" enctype="multipart/form-data" onsubmit="return formVal.validate();">

<h4>Добавляем событие</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Бренд:</TD>
  <TD><INPUT type="text" size="5" name="brands_id" id="brands_id"> <INPUT type="button" onclick="objBrandsList.showPopUp();" value="Выбрать"> <SPAN id="brands_id_name"></SPAN></TD>
 </TR>
 <TR>
  <TD>Город:</TD>
  <TD><INPUT type="text" size="5" name="cities_id" id="cities_id" onchange="createExpoCentersList(this.value);"> <INPUT type="button" onclick="objCitiesList.showPopUp();" value="Выбрать"> <SPAN id="cities_id_name"></SPAN></TD>
 </TR>
 <TR>
  <TD>Выставочный центр:</TD>
  <TD>
   <SELECT name="expocenters_id" id="expocenters_id">
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD>Номер события:</TD>
  <TD><INPUT type="text" size="5" name="number"></TD>
 </TR>
 <TR>
  <TD>Даты проведения:</TD>
  <TD>с <INPUT type="text" size="12" name="date_from" id="date_from" onchange="$('#date_to').val(this.value);"> по <INPUT type="text" size="12" name="date_to" id="date_to"></TD>
 </TR>
 <TR>
  <TD>Период проведения:</TD>
  <TD>
   <SELECT name="periods_id">
    <OPTION value="">(Не выбрано)</OPTION>
    {foreach from=$list_periods item="el"}
     <OPTION value="{$el.id}">{$el.name}</OPTION>
    {/foreach}
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD>Логотип:</TD>
  <TD><INPUT type="file" name="logo"></TD>
 </TR>
 <TR>
  <TD valign="top">Время работы:</TD>
  <TD><TEXTAREA name="work_time" style="width:90%; height:50px;"></TEXTAREA></TD>
 </TR>
 <TR>

 <TR>
  <TD>Вход на выставку:</TD>
  <TD>
    <SELECT name="is_free">
     <OPTION value="1">Бесплатный</OPTION>
     <OPTION value="0">Платный</OPTION>
   </SELECT>
  </TD>
 </TR>

 <TR>
  <TD>Стоимость входа:</TD>
  <TD><INPUT type="text" name="ticket_fee"></TD>
 </TR>
 <TR>
  <TD>Сайт выставки:</TD>
  <TD><INPUT type="text" name="url" size="50"></TD>
 </TR>
 <TR>
  <TD>Депозит (Buyer program):</TD>
  <TD><INPUT type="text" name="deposit_buyer" size="10" value="0.00"></TD>
 </TR>


 <TR>
  <TD colspan="2"><a name="anchor_description"><!-- --></a>Описание:<BR />
   <TEXTAREA name="description" id="description" style="width:95%; height:250px;" ></TEXTAREA>
  </TD>
 </TR>

 <TR>
  <TD colspan="2">Тематически секции:<BR />
   <TEXTAREA name="thematic_sections" id="thematic_sections" style="width:95%; height:500px;"></TEXTAREA>
  </TD>
 </TR>

 <TR>
  <TD colspan="2" valign="top">

   <div>Контакты проектной группы:</div>
   <TABLE border="1" width="45%" style="border-collapse:collapse; float:left;">
    <TR>
     <TD>Email: </TD>
     <TD><INPUT type="text" name="email" id="email" size="25"></TD>
    </TR>
    <TR>
     <TD>Адрес сайта: </TD>
     <TD><INPUT type="text" name="web_address" id="web_address" size="25"></TD>
    </TR>
    <TR>
     <TD>Телефон: </TD>
     <TD><INPUT type="text" name="phone" id="phone" size="25"></TD>
    </TR>
    <TR>
     <TD>Факс: </TD>
     <TD><INPUT type="text" name="fax" id="fax" size="25"></TD>
    </TR>
   </TABLE>

   <TABLE border="1" width="45%" style="border-collapse:collapse; float:right;">
    <TR>
     <TD>Контактное лицо: </TD>
     <TD><INPUT type="text" name="cont_pers_name" size="25"></TD>
    </TR>
    <TR>
     <TD>Телефон КЛ: </TD>
     <TD><INPUT type="text" name="cont_pers_phone" size="25"></TD>
    </TR>
    <TR>
     <TD>Email КЛ: </TD>
     <TD><INPUT type="text" name="cont_pers_email" size="25"></TD>
    </TR>
   </TABLE>

  </TD>
 </TR>
 <TR>
  <TD colspan="2" valign="top">

   <div>Дополнительная информация о событии:</div>
   <TABLE border="1" width="45%" style="border-collapse:collapse; float:left;">
    <TR>
     <TD>Участников всего:</TD>
     <TD><INPUT type="text" name="partic_num" size="10"></TD>
    </TR>
    <TR>
     <TD>Местных участников:</TD>
     <TD><INPUT type="text" name="local_partic_num" size="10"></TD>
    </TR>
    <TR>
     <TD>Иностранных участников:</TD>
     <TD><INPUT type="text" name="foreign_partic_num" size="10"></TD>
    </TR>
    <TR>
     <TD>Общая площадь:</TD>
     <TD><INPUT type="text" name="s_event_total" size="10"></TD>
    </TR>
   </TABLE>

   <TABLE border="1" width="45%" style="border-collapse:collapse; float:right;">
    <TR>
     <TD>Посетителей всего:</TD>
     <TD><INPUT type="text" name="visitors_num" size="10"></TD>
    </TR>
    <TR>
     <TD>Местных посетителей:</TD>
     <TD><INPUT type="text" name="local_visitors_num" size="10"></TD>
    </TR>
    <TR>
     <TD>Иностранных посетителей:</TD>
     <TD><INPUT type="text" name="foreign_visitors_num" size="10"></TD>
    </TR>
   </TABLE>

  </TD>
 </TR>
 <TR>
  <TD>Отображать логотип в списке:</TD>
  <TD><INPUT type="checkbox" onclick="Shelby_Backend.objects_multi_checkbox('show_list_logo', this.checked);">
<INPUT type="hidden" name="show_list_logo" id="show_list_logo" value="0"></TD>
 </TR>
 <TR>
  <TD>Запросы на карточке:</TD>
  <TD>
   {foreach from=$list_requests item="el"}
    <INPUT type="checkbox" name="user_request_types[{$el.id}]" value="{$el.id}" checked> {$el.name}<BR />
   {/foreach}
  </TD>
 </TR>

 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Добавить"></TD></TR>
</TABLE>

</FORM>