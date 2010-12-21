{include file="common/contentVisualEdit.tpl" textarea="thematic_sections_ru,description_ru,thematic_sections_en,description_en" imagesDefaultParent="events:`$entry.id`"}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>
<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/jqExtensions/ui.datepicker.js"></SCRIPT>

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

function createExpoCentersList(id) {
	var url = '{/literal}{getUrl controller="admin_ep_expocenters" action="list" results="999" feed="json"}{literal}search/cities_id:' + id + '/';

	$.getJSON(url, function(json) {
		var tmp = '<SELECT name="common[expocenters_id]"><option value="">(Не установлено)</option>';
		$.each(json.list.data, function(i) {
			tmp += '<option value="' + json.list.data[i].id + '"';

			if (json.list.data[i].id==parseInt({/literal}{$entry[$selected_language].expocenters_id}{literal})) {
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

	createExpoCentersList({/literal}{$entry[$selected_language].cities_id}{literal});

});


checkLength = function ()
{
  tinyMCE.triggerSave(false, true);

  var descriptions = $('[id^=description_en]');
  var descr;

  for ( var i = 0; i < descriptions.length; i++ )
  {
    descr = descriptions.eq(i);
    var length = $('<span/>').html(descr.val()).text().length;
    if ( length < 300 )
    {
      location = '#anchor_'+descr.attr('id');
      tinyMCE.execCommand('mceFocus', false, descr.attr('id'));
      alert('Описание выставки должно быть минимум 300 символов.\nСейчас введено ' + length + ' символ(ов).');
      return false;
    }
  }
  return true;
}

{/literal}
</SCRIPT>

<H4>Копирование события</H4>

<FORM action="{getUrl add="1" del="id" action="insert"}" method="post" onsubmit="return checkLength();">
<INPUT type="hidden" name="_shelby_insert_all_langs" value="1" />

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Бренд:</TD>
  <TD><INPUT type="text" size="5" name="common[brands_id]" id="brands_id" value="{$entry[$selected_language].brands_id}"> <INPUT type="button" onclick="objBrandsList.showPopUp();" value="Выбрать"> <SPAN id="brands_id_name">{$entry[$selected_language].brand_name}<BR />{$entry[$selected_language].brand_name_extended}</SPAN></TD>
 </TR>
 <TR>
  <TD>Город:</TD>
  <TD><INPUT type="text" size="5" name="common[cities_id]" id="cities_id" value="{$entry[$selected_language].cities_id}" onchange="createExpoCentersList(this.value);"> <INPUT type="button" onclick="objCitiesList.showPopUp();" value="Выбрать"> <SPAN id="cities_id_name">{$entry[$selected_language].city_name}</SPAN></TD>
 </TR>
 <TR>
  <TD>Выставочный центр:</TD>
  <TD id="expocenters_id"></TD>
 </TR>
 <TR>
  <TD>Даты проведения:</TD>
  <TD>с <INPUT type="text" size="12" name="common[date_from]" id="date_from" onchange="$('#date_to').val(this.value);" value="{$entry[$selected_language].date_from}"> по <INPUT type="text" size="12" name="common[date_to]" id="date_to" value="{$entry[$selected_language].date_to}"></TD>
 </TR>
 <TR>
  <TD>Период проведения:</TD>
  <TD>
   <SELECT name="common[periods_id]">
    <OPTION value="">(Не выбрано)</OPTION>
    {foreach from=$list_periods item="el"}
     <OPTION value="{$el.id}"{if $entry[$selected_language].periods_id==$el.id} selected{/if}>{$el.name}</OPTION>
    {/foreach}
   </SELECT>
  </TD>
 </TR>

 <TR>
  <TD>Вход на выставку:</TD>
  <TD>
    <SELECT name="common[is_free]">
     <OPTION value="">Не выбрано</OPTION>
     <OPTION value="1" {if $entry[$selected_language].is_free === '1'}selected{/if}>Бесплатный</OPTION>
     <OPTION value="0" {if $entry[$selected_language].is_free === '0'}selected{/if}>Платный</OPTION>
   </SELECT>
  </TD>
 </TR>

 <TR>
  <TD>Стоимость входа:</TD>
  <TD><INPUT type="text" name="common[ticket_fee]" value="{$entry[$selected_language].ticket_fee|escape:"html"}"></TD>
 </TR>
 <TR>
  <TD>Сайт выставки:</TD>
  <TD><INPUT type="text" name="common[url]" size="50" value="{$entry[$selected_language].url|escape:"html"}"></TD>
 </TR>
</TABLE>

{foreach from=$list_languages item="lang"}
 <h5 align="center">{$lang.name}</h5>
 {include file="controllers/admin_ep_events/copy_lang_spec.tpl" lang=$lang.code}
{/foreach}

<BR />
<CENTER>
 <INPUT type="submit" value="Копировать" />
</CENTER>
</FORM>