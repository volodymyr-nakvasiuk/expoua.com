{include file="common/contentVisualEdit.tpl" textarea="thematic_sections,description" imagesDefaultParent="events:`$entry.id`"}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

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

{literal}
objCitiesList.callbackUser = function(entry) {
	createExpoCentersList(entry.id);
}

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

function createExpoCentersList(id) {
	var url = '{/literal}{getUrl controller="admin_ep_expocenters" action="list" results="1000" sort="name:ASC" feed="json"}{literal}search/cities_id:' + id + '/';

	$.getJSON(url, function(json) {
		var tmp = '<SELECT name="expocenters_id"><option value="">(Не установлено)</option>';
		$.each(json.list.data, function(i) {
			tmp += '<option value="' + json.list.data[i].id + '"';

			if (json.list.data[i].id==parseInt({/literal}{$entry.expocenters_id}{literal})) {
				tmp += ' selected="selected"';
			}

			tmp += '>' + json.list.data[i].name + '</option>';

		});
		tmp += "</SELECT>";
		$("#expocenters_id").html(tmp);
	});
}

$(document).ready(function(){

	$('#date_from').datepicker();
	$('#date_to').datepicker();

	createExpoCentersList({/literal}{$entry.cities_id}{literal});
});

checkLength = function ()
{
  tinyMCE.triggerSave(false, true);
  var length = $('<span/>').html($('#description').val()).text().length;
  if ( length < 300 )
  {
    location = '#anchor_description';
    tinyMCE.execCommand('mceFocus', false, 'description');
    alert('Описание выставки должно быть минимум 300 символов.\nСейчас введено ' + length + ' символ(ов).');
    return false;
  }
  return true;
}

function deleteImage() {
	$('#logo_td_holder').html('<input type="hidden" name="logo" value="0" />Будет удален при сохранении для текущего языка');
}

{/literal}
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="update"}" enctype="multipart/form-data"{if $selected_language=='en'} onsubmit="return checkLength();"{/if}>

<h4>Редактируем событие</h4>

<DIV align="right"><a href="{getUrl controller="admin_ep_eventsfiles" action="list" event_id=$entry.id}">Прикрепленные файлы</A></DIV>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Бренд:</TD>
  <TD><INPUT type="text" size="5" name="brands_id" id="brands_id" value="{$entry.brands_id}"> <INPUT type="button" onclick="objBrandsList.showPopUp();" value="Выбрать"> <SPAN id="brands_id_name">{$entry.brand_name}<BR />{$entry.brand_name_extended}</SPAN></TD>
 </TR>
 <TR>
  <TD>Город:</TD>
  <TD><INPUT type="text" size="5" name="cities_id" id="cities_id" value="{$entry.cities_id}" onchange="createExpoCentersList(this.value);"> <INPUT type="button" onclick="objCitiesList.showPopUp();" value="Выбрать"> <SPAN id="cities_id_name">{$entry.city_name}</SPAN></TD>
 </TR>
 <TR>
  <TD>Выставочный центр:</TD>
  <TD id="expocenters_id"></TD>
 </TR>
 <TR>
  <TD>Номер события:</TD>
  <TD><INPUT type="text" size="5" name="number" value="{$entry.number}"></TD>
 </TR>
 <TR>
  <TD>Даты проведения:</TD>
  <TD>с <INPUT type="text" size="12" name="date_from" value="{$entry.date_from}" id="date_from" onchange="$('#date_to').val(this.value);"> по <INPUT type="text" size="12" name="date_to" value="{$entry.date_to}" id="date_to"></TD>
 </TR>
 <TR>
  <TD>Период проведения:</TD>
  <TD>
   <SELECT name="periods_id">
    <OPTION value="">(Не выбрано)</OPTION>
    {foreach from=$list_periods item="el"}
     <OPTION value="{$el.id}" {if $el.id==$entry.periods_id}selected{/if}>{$el.name}</OPTION>
    {/foreach}
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD>Логотип:</TD>
  <TD id="logo_td_holder">
   <INPUT type="file" name="logo">
   {if $entry.logo==1}
    <a href="/data/images/events/logo/{$entry.languages_id}/{$entry.id}.jpg" target="_blank">Просмотреть</a> &nbsp; &nbsp;
    <a href="#" onclick="deleteImage(); return false;">Удалить</a>
   {/if}
  </TD>
 </TR>
 <TR>
  <TD valign="top">Время работы:</TD>
  <TD><TEXTAREA name="work_time" style="width:90%; height:50px;">{$entry.work_time|escape:"html"}</TEXTAREA></TD>
 </TR>
 <TR>

 <TR>
  <TD>Вход на выставку:</TD>
  <TD>
    <SELECT name="is_free">
     <OPTION value="">Не выбрано</OPTION>
     <OPTION value="1" {if $entry.is_free === '1'}selected{/if}>Бесплатный</OPTION>
     <OPTION value="0" {if $entry.is_free === '0'}selected{/if}>Платный</OPTION>
   </SELECT>
  </TD>
 </TR>

 <TR>
  <TD>Стоимость входа:</TD>
  <TD><INPUT type="text" name="ticket_fee" value="{$entry.ticket_fee|escape:"html"}"></TD>
 </TR>
 <TR>
  <TD>Сайт выставки:</TD>
  <TD><INPUT type="text" name="url" size="50" value="{$entry.url|escape:"html"}"></TD>
 </TR>
 <TR>
  <TD>Депозит (Buyer program):</TD>
  <TD><INPUT type="text" name="deposit_buyer" size="10" value="{$entry.deposit_buyer}"></TD>
 </TR>

 <TR>
  <TD colspan="2"><a name="anchor_description"><!-- --></a>Описание:<BR />
   <TEXTAREA name="description" id="description" style="width:95%; height:250px;">{$entry.description|escape:"html"}</TEXTAREA>
  </TD>
 </TR>

 <TR>
  <TD colspan="2">Тематически секции:<BR />
   <TEXTAREA name="thematic_sections" id="thematic_sections" style="width:95%; height:500px;">{$entry.thematic_sections|escape:"html"}</TEXTAREA>
  </TD>
 </TR>

 <TR>
  <TD colspan="2" valign="top">

   <div>Контакты проектной группы:</div>
   <TABLE border="1" width="45%" style="border-collapse:collapse; float:left;">
    <TR>
     <TD>Email: </TD>
     <TD><INPUT type="text" name="email" id="email" value="{$entry.email}" size="25"></TD>
    </TR>
    <TR>
     <TD>Адрес сайта: </TD>
     <TD>
      <INPUT type="text" name="web_address" id="web_address" value="{$entry.web_address}" size="25">
      (<a href="{$entry.web_address}" target="_blank">перейти</a>)
     </TD>
    </TR>
    <TR>
     <TD>Телефон: </TD>
     <TD><INPUT type="text" name="phone" id="phone" value="{$entry.phone}" size="25"></TD>
    </TR>
    <TR>
     <TD>Факс: </TD>
     <TD><INPUT type="text" name="fax" id="fax" value="{$entry.fax}" size="25"></TD>
    </TR>
   </TABLE>

   <TABLE border="1" width="45%" style="border-collapse:collapse; float:right;">
    <TR>
     <TD>Контактное лицо: </TD>
     <TD><INPUT type="text" name="cont_pers_name" value="{$entry.cont_pers_name}" size="25"></TD>
    </TR>
    <TR>
     <TD>Телефон КЛ: </TD>
     <TD><INPUT type="text" name="cont_pers_phone" value="{$entry.cont_pers_phone}" size="25"></TD>
    </TR>
    <TR>
     <TD>Email КЛ: </TD>
     <TD><INPUT type="text" name="cont_pers_email" value="{$entry.cont_pers_email}" size="25"></TD>
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
     <TD><INPUT type="text" name="partic_num" value="{$entry.partic_num}" size="10"></TD>
    </TR>
    <TR>
     <TD>Местных участников:</TD>
     <TD><INPUT type="text" name="local_partic_num" value="{$entry.local_partic_num}" size="10"></TD>
    </TR>
    <TR>
     <TD>Иностранных участников:</TD>
     <TD><INPUT type="text" name="foreign_partic_num" value="{$entry.foreign_partic_num}" size="10"></TD>
    </TR>
    <TR>
     <TD>Общая площадь:</TD>
     <TD><INPUT type="text" name="s_event_total" value="{$entry.s_event_total}" size="10"></TD>
    </TR>
   </TABLE>

   <TABLE border="1" width="45%" style="border-collapse:collapse; float:right;">
    <TR>
     <TD>Посетителей всего:</TD>
     <TD><INPUT type="text" name="visitors_num" value="{$entry.visitors_num}" size="10"></TD>
    </TR>
    <TR>
     <TD>Местных посетителей:</TD>
     <TD><INPUT type="text" name="local_visitors_num" value="{$entry.local_visitors_num}" size="10"></TD>
    </TR>
    <TR>
     <TD>Иностранных посетителей:</TD>
     <TD><INPUT type="text" name="foreign_visitors_num" value="{$entry.foreign_visitors_num}" size="10"></TD>
    </TR>
   </TABLE>

  </TD>
 </TR>

 <TR>
  <TD>Отображать логотип в списке:</TD>
  <TD><INPUT type="checkbox"{if $entry.show_list_logo==1} checked{/if} onclick="Shelby_Backend.objects_multi_checkbox('show_list_logo', this.checked);">
<INPUT type="hidden" name="show_list_logo" id="show_list_logo" value="{if $entry.show_list_logo==1}1{else}0{/if}"></TD>
 </TR>

 <TR>
  <TD>Бесплатные билеты на выставку:</TD>
  <TD><INPUT type="checkbox"{if $entry.free_tickets==1} checked{/if} onclick="Shelby_Backend.objects_multi_checkbox('free_tickets', this.checked);">
<INPUT type="hidden" name="free_tickets" id="free_tickets" value="{if $entry.free_tickets==1}1{else}0{/if}"></TD>
 </TR>

 <TR>
  <TD>Запросы на карточке:</TD>
  <TD>
   <INPUT type="hidden" name="_shelby_user_requests" value="1" />
   {foreach from=$list_requests item="el"}
    <INPUT type="checkbox" name="user_request_types[{$el.id}]" value="{$el.id}"{if isset($entry.user_request_types[$el.id])} checked{/if}> {$el.name}<BR />
   {/foreach}
  </TD>
 </TR>

 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Обновить"></TD></TR>
</TABLE>

</FORM>