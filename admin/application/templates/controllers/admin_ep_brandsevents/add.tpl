{strip}{assign var="ve_textareas" value=""}
{foreach from=$list_languages item="lang" name="lang_fe"}
{assign var="ve_textareas" value="`$ve_textareas`thematic_sections_`$lang.code`,description_`$lang.code`"}
{if !$smarty.foreach.lang_fe.last}{assign var="ve_textareas" value="`$ve_textareas`,"}{/if}
{/foreach}{/strip}
{include file="common/orgContentVisualEdit.tpl" textarea=$ve_textareas}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>
<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/jqExtensions/ui.datepicker.js"></SCRIPT>
<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminFormValidator.js"></SCRIPT>

<SCRIPT type="text/javascript" language="javascript">

objOrganizersList = Shelby_Backend.ListHelper.cloneObject('objOrganizersList');
objCitiesList = Shelby_Backend.ListHelper.cloneObject('objCitiesList');
objExpocentersList = Shelby_Backend.ListHelper.cloneObject('objExpocentersList');

objOrganizersList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('city_name', 'Город'));
objOrganizersList.returnFieldId = 'brand_organizers_id';
objOrganizersList.feedUrl = '{getUrl controller="sab_jsonfeeds" action="organizers"}';
objOrganizersList.writeForm();

objCitiesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('country_name', 'Страна'));
objCitiesList.returnFieldId = 'cities_id';
objCitiesList.feedUrl = '{getUrl controller="sab_jsonfeeds" action="cities"}';
objCitiesList.writeForm();

{literal}

objCitiesList.callbackUser = function(entry) {
	createExpoCentersList(entry.id);
}

function copyField(lang, field, value) {
	var array_langs = {/literal}{$HMixed->toJson($list_languages)}{literal};
	$.each(array_langs, function() {
		if (this.code != lang) {
			if ($("#" + field + this.code).val() == "") {
				$("#" + field + this.code).val(value);
			}
		}
	});
}

objOrganizersList.ajaxFillContacts = function(lang, id) {
	var url = "/" + lang + "/sab_jsonfeeds/organizer/id/" + id + "/";
	$.getJSON(url, function(json) {
		$("#email_" + lang).val(json.entry.email);
		$("#web_address_" + lang).val(json.entry.web_address);
		$("#phone_" + lang).val(json.entry.phone);
		$("#fax_" + lang).val(json.entry.fax);
	});
}

objOrganizersList.callbackUser = function(entry) {
	$("#brand_organizers_id_name").append(' (<a target="_blank" href="{/literal}{getUrl controller="admin_ep_brands" action="list" sort="name:ASC"}{literal}search/organizers_id=' + entry.id + '/">Показать все бренды</a>)');
	{/literal}
	{foreach from=$list_languages item="lang"}this.ajaxFillContacts("{$lang.code}", entry.id);{/foreach}
	{literal}
}

function createExpoCentersList(id) {
	var url = '{/literal}{getUrl controller="sab_jsonfeeds" action="expocenters" results="1000" sort="name:ASC"}{literal}search/cities_id:' + id + '/';

	$.getJSON(url, function(json) {
		var tmp = '<SELECT name="common[expocenters_id]"><option value="">(Не установлено)</option>';
		$.each(json.list.data, function(i) {
			tmp += '<option value="' + json.list.data[i].id + '"';
			tmp += '>' + json.list.data[i].name + '</option>';
		});
		tmp += "</SELECT>";
		$("#expocenters_id").html(tmp);
	});
}

function createSubcategoriesList(id) {
	$("#brand_subcategories_id").empty();
	$("#brand_subcategories_id").attr("disabled", "disabled");
	if (id > 0) {
		var url = "{/literal}{getUrl controller="sab_jsonfeeds" action="brandssubcategories" sort="name:ASC"}{literal}parent/" + id + "/";
		$.getJSON(url, function(json) {
			$.each(json.list.data, function(i) {
				$("#brand_subcategories_id").append("<option value='" + json.list.data[i].id + "'>" + json.list.data[i].name + "</option>");
			});
			$("#brand_subcategories_id").removeAttr("disabled");
		});
	}
}

checkLength = function () {
  tinyMCE.triggerSave(false, true);

  var descriptions = $('[id^=description_en]');
  var descr;

  for ( var i = 0; i < descriptions.length; i++ ) {
    descr = descriptions.eq(i);
    var length = $('<span/>').html(descr.val()).text().length;
    if ( length < 300 ) {
      location = '#anchor_'+descr.attr('id');
      tinyMCE.execCommand('mceFocus', false, descr.attr('id'));
      return '- Описание выставки должно быть минимум 300 символов.\nСейчас введено ' + length + ' символ(ов).\n';
    }
  }
  return true;
}

function checkBrandExistents(name, lang) {
	if (name == "") {
		return;
	}
	var url = "/" + lang + "/sab_jsonfeeds/brands/search/name~" + name + "/";
	$.getJSON(url, function(json) {
		if (json.list.rows > 0) {
			$("#note_brands_found_" + lang).html('<a target="_blank" href="/' + lang + '/admin_ep_brands/list/search/name~' + name + '/">Найдено похожих: ' + json.list.rows + '</a>');
		} else {
			$("#note_brands_found_" + lang).html('');
		}
	});
}

formVal = Shelby_Backend.FormValidator.cloneObject("formVal");
formVal.headerMessage = "{/literal}{#msgThereAreErrors#}{literal}:\n";
formVal.addField("cities_id", "num", 1, "- {/literal}{#msgChooseCity#}{literal}\n");
formVal.addField("brand_organizers_id", "num", 1, "- {/literal}Выберите организатора{literal}\n");
formVal.userValidation = checkLength;

$(document).ready(function() {
	$('#date_from').datepicker();
	$('#date_to').datepicker();

	createSubcategoriesList($("#brand_categories_id").val());
});
{/literal}
</SCRIPT>

<H4>Добавляем новый бренд+событие</H4>

<FORM action="{getUrl add="1" action="insert"}" method="post" onsubmit="return formVal.validate();">

<TABLE border="0" width="100%" style="border-collapse:collapse;" cellpadding="5">
 <TR>
 <TD valign="top">
 
<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Бренд:</TD>
  <TD>
  <TABLE width="100%">
  <TR>
   <TD>Организатор: </TD>
   <TD><input type="text" size="5" name="common[organizers_id]" id="brand_organizers_id"> <INPUT type="button" onclick="objOrganizersList.showPopUp();" value="Выбрать"> <SPAN id="brand_organizers_id_name"></SPAN></TD>
  </TR>
 {foreach from=$list_languages item="lang"}
 <TR>
  <TD width="200">Название ({$lang.name}):</TD>
  <TD><input type="text" size="60" name="{$lang.code}[name]" id="brand_name_{$lang.code}" onblur="checkBrandExistents(this.value, '{$lang.code}');" /><span id="note_brands_found_{$lang.code}" style="background-color:lightgreen;"></span></TD>
 </TR>
 {/foreach}
 {foreach from=$list_languages item="lang"}
 <TR>
  <TD width="200" valign="top">Расширенное название ({$lang.name}):</TD>
  <TD><textarea name="{$lang.code}[name_extended]" id="brand_name_extended_{$lang.code}" style="width:90%; height:40px;"></textarea></TD>
 </TR>
 {/foreach}

 <TR>
  <TD width="200">Категория:</TD>
  <TD>
   <SELECT name="common[brands_categories_id]" id="brand_categories_id" onchange="createSubcategoriesList(this.value);">
   {foreach from=$list_brand_categories item="el"}
    <OPTION value="{$el.id}">{$el.name}</OPTION>
   {/foreach}
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD width="200">Подкатегории:<br/>(удерживайте CTRL чтобы выбрать несколько)</TD>
  <TD>
  <SELECT name="common[brand_subcategories_id][]" id="brand_subcategories_id" multiple="multiple" disabled="disabled" style="width:90%; height:80px;"></SELECT>
  </TD>
 </TR>
 </TABLE>

  </TD>
 </TR>
 <TR>
  <TD>Город:</TD>
  <TD>
   <INPUT type="text" size="5" name="common[cities_id]" id="cities_id" value="" onchange="createExpoCentersList(this.value);"> <INPUT type="button" onclick="objCitiesList.showPopUp();" value="Выбрать">
   <SPAN id="cities_id_name"></SPAN>
  </TD>
 </TR>
 <TR>
  <TD>Выставочный центр:</TD>
  <TD id="expocenters_id"></TD>
 </TR>
 <TR>
  <TD>Даты проведения:</TD>
  <TD>с <INPUT type="text" size="12" name="common[date_from]" id="date_from" onchange="$('#date_to').val(this.value);"> по <INPUT type="text" size="12" name="common[date_to]" id="date_to"></TD>
 </TR>
 <TR>
  <TD>Период проведения:</TD>
  <TD>
   <SELECT name="common[periods_id]">
    <OPTION value="">(Не выбрано)</OPTION>
    {foreach from=$list_periods item="el"}
     <OPTION value="{$el.id}">{$el.name}</OPTION>
    {/foreach}
   </SELECT>
  </TD>
 </TR>
 <tr>
  <td>Вход на выставку:</td>
  <td>
   <SELECT name="common[is_free]">
   	 <OPTION value="">(Не выбрано)</OPTION>
     <OPTION value="1">Бесплатный</OPTION>
     <OPTION value="0">Платный</OPTION>
   </SELECT>
  </td>
 </tr>
 <tr>
  <td>Стоимость входа:</td>
  <td><INPUT type="text" name="common[ticket_fee]"></td>
 </tr>
 
 <TR>
  <TD colspan="2" valign="top">

   <div>Дополнительная информация о событии:</div>
   <TABLE style="border-collapse:collapse;">
    <TR>
     <TD>Участников всего:</TD>
     <TD><INPUT type="text" name="common[partic_num]" size="10"></TD>
    </TR>
    <TR>
     <TD>Местных участников:</TD>
     <TD><INPUT type="text" name="common[local_partic_num]" size="10"></TD>
    </TR>
    <TR>
     <TD>Иностранных участников:</TD>
     <TD><INPUT type="text" name="common[foreign_partic_num]" size="10"></TD>
    </TR>
    <TR>
     <TD>Общая площадь:</TD>
     <TD><INPUT type="text" name="common[s_event_total]" size="10"></TD>
    </TR>
    <TR>
     <TD>Посетителей всего:</TD>
     <TD><INPUT type="text" name="common[visitors_num]" size="10"></TD>
    </TR>
    <TR>
     <TD>Местных посетителей:</TD>
     <TD><INPUT type="text" name="common[local_visitors_num]" size="10"></TD>
    </TR>
    <TR>
     <TD>Иностранных посетителей:</TD>
     <TD><INPUT type="text" name="common[foreign_visitors_num]" size="10"></TD>
    </TR>
   </TABLE>

  </TD>
 </TR>
</TABLE>

 </TD>
</TR>
</TABLE>

 <table width="100%">
 <tr>
 {foreach from=$list_languages item="lang"}
  <td width="50%">
   <h5 align="center">{$lang.name}</h5>
   {include file="controllers_frontend/sab_operator_drafts/add_lang_spec.tpl" lang=$lang.code}
  </td>
 {/foreach}
 </tr>
 </table>

<BR />
<CENTER>
 <INPUT type="submit" value="Добавить" />
</CENTER>

</FORM>