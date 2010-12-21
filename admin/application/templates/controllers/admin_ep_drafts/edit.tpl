{strip}{assign var="ve_textareas" value=""}
{foreach from=$list_languages item="lang" name="lang_fe"}
{assign var="ve_textareas" value="`$ve_textareas`thematic_sections_`$lang.code`,description_`$lang.code`"}
{if !$smarty.foreach.lang_fe.last}{assign var="ve_textareas" value="`$ve_textareas`,"}{/if}
{/foreach}{/strip}
{include file="common/contentVisualEdit.tpl" textarea=$ve_textareas imagesDefaultParent="events_operators"}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>
<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/jqExtensions/ui.datepicker.js"></SCRIPT>

<SCRIPT type="text/javascript" language="javascript">

objBrandsList = Shelby_Backend.ListHelper.cloneObject('objBrandsList');
objOrganizersList = Shelby_Backend.ListHelper.cloneObject('objOrganizersList');
objCitiesList = Shelby_Backend.ListHelper.cloneObject('objCitiesList');
objExpocentersList = Shelby_Backend.ListHelper.cloneObject('objExpocentersList');

objBrandsList.columns = new Array(new Array('id', 'Id'), new Array('name', 'Название'), new Array('organizer_name', 'Организатор'));
objBrandsList.returnFieldId = 'brands_id';
objBrandsList.feedUrl = '{getUrl controller="admin_ep_brands" action="list" feed="json"}';
objBrandsList.writeForm();

objOrganizersList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('city_name', 'Город'));
objOrganizersList.returnFieldId = 'brand_organizers_id';
objOrganizersList.feedUrl = '{getUrl controller="admin_ep_organizers" action="list" feed="json"}';
objOrganizersList.writeForm();

objCitiesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('country_name', 'Страна'));
objCitiesList.returnFieldId = 'cities_id';
objCitiesList.feedUrl = '{getUrl controller="admin_ep_locations_cities" action="list" feed="json"}';
objCitiesList.writeForm();

{literal}

objCitiesList.callbackUser = function(entry) {
	createExpoCentersList(entry.id);
}

objBrandsList.callbackUser = function(entry) {
	$("#brand_actions input:radio").removeAttr("disabled");
}

function createExpoCentersList(id) {
	var url = '{/literal}{getUrl controller="admin_ep_expocenters" results="999" action="list" sort="name:ASC" feed="json"}{literal}search/cities_id:' + id + '/';

	$.getJSON(url, function(json) {
		var tmp = '<SELECT name="common[expocenters_id]" style="width:98%;"><option value="">(Не установлено)</option>';
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

function createBrendCategories() {
	var url = '{/literal}{getUrl controller="sab_jsonfeeds" action="brandscategories"}{literal}';

	$.getJSON(url, function(json) {
		var tmp = '<SELECT name="common[brand_categories_id]" style="width:98%;">';
		$.each(json.list.data, function(i) {
			tmp += '<option value="' + json.list.data[i].id + '"';
			if (json.list.data[i].id==parseInt({/literal}{$entry[$selected_language].brand_categories_id}{literal})) {
				tmp += ' selected';
			}
			tmp += '>' + json.list.data[i].name + '</option>';

		});
		tmp += "</SELECT>";
		$("#brand_categories_holder").html(tmp);
	});
}

function acceptHigh() {
	$('#accept_type').val('high');
	$('#mainform').submit();
}

$(document).ready(function(){

	$('#date_from').datepicker();
	$('#date_to').datepicker();

	{/literal}
	createExpoCentersList({$entry[$selected_language].cities_id});
	{if is_null($entry.brands_id)}createBrendCategories();{/if}
	{literal}

});
{/literal}
</SCRIPT>


<H4>Редактирование черновика</H4>

{if $entry[$selected_language].premium==1}<H4 style="color:red; text-align:center;">Премиум-пакет</H4>{/if}

{if !empty($entry[$selected_language].events_id)}
  <DIV align="right"><a href="{getUrl controller="admin_ep_events" action="edit" id=$entry[$selected_language].events_id}" target="_blank">Показать исходное событие</a></DIV>
{/if}

<TABLE cellspacing="2" width="100%">
<TR>
  <TD width="50%" valign="top">
    {include file="controllers/admin_ep_drafts/base_event.tpl"}
  </TD>

  <TD width="50%" valign="top">
    {if $entry[$selected_language].type=="add"}
    <FORM action="{getUrl add="1" del="id" action="insert"}" method="post" id="mainform">
    {elseif $entry[$selected_language].type=="edit"}
    <FORM action="{getUrl add="1" action="update" type="update_event" id=$entry[$selected_language].events_id}" method="post" id="mainform">
    {/if}
    <INPUT type="hidden" name="draft_id" value="{$entry[$selected_language].id}" />
    
    <fieldset>
      <TABLE cellspacing="2" width="100%">
      <TR>
        <TD style="width:35%;">Бренд:</TD>
        <TD style="width:65%;">
          Организатор: <input type="text" size="5" name="common[brand_organizers_id]" id="brand_organizers_id" value="{$entry[$selected_language].brand_organizers_id}"> <INPUT type="button" onclick="objOrganizersList.showPopUp();" value="Выбрать"> <SPAN id="brand_organizers_id_name">{$entry[$selected_language].organizer_name}</SPAN><BR />
          Категория: <SPAN id="brand_categories_holder"></SPAN><br/>
      
          {if !empty($entry.brands_subcategories)}
           <b>Выбранные подкатегории:</b><br/>
           {foreach from=$entry.brands_subcategories item="el" key="key"}
            <INPUT type="checkbox" name="common[brand_subcategories_id][]" value="{$key}" checked="checked"/> {$el}<br/>
           {/foreach}
          {/if}
      
          {foreach from=$list_languages item="lang"}
            <BR />Название ({$lang.name}): <input type="text" size="60" name="{$lang.code}[brand_name_new]" value="{$entry[$lang.code].brand_name_new}" id="brand_name_new_{$lang.code}"><BR />
            Расширенное название ({$lang.name}):<BR />
            <textarea name="{$lang.code}[brand_name_extended_new]" id="brand_name_extended_new{$lang.code}" style="width:90%; height:40px;">{$entry[$lang.code].brand_name_extended_new|escape:"html"}</textarea>
          {/foreach}
          
          {foreach from=$list_languages item="lang"}
           {if $entry[$lang.code].logo == 1}
            <br/>Загужен логотип ({$lang.name}):
            <a href="/data/images/drafts_events/logo/{$lang.id}/{$entry[$lang.code].id}.jpg" target="_blank"><b>просмотреть</b></a>
            добавить: <input type="checkbox" name="{$lang.code}[logo]" value="1" checked="checked"/>
           {/if}
          {/foreach}
          
          <br /><strong>Существующий бренд:</strong>
      
          <INPUT type="text" size="5" name="common[brands_id]" id="brands_id" value="{$entry[$selected_language].brands_id}">
          <INPUT type="button" onclick="objBrandsList.showPopUp();" value="Выбрать">
          <div id="brands_id_name">
            {foreach from=$list_languages item="lang"}
            <div style="margin:5px 0;">
              {$lang.code|upper}: {$entry[$lang.code].brand_name}{if $entry[$lang.code].brand_name_extended} ({$entry[$lang.code].brand_name_extended}){/if}
            </div>
            {/foreach}
          </div>
          <div id="brand_actions"><strong>Что делать с брендом:</strong><br />
            <INPUT type="radio" name="_shelby_brand_action" value="0" {if empty($entry[$selected_language].brands_id)}disabled{else}checked{/if}><LABEL>Оставить как есть</LABEL>
            <INPUT type="radio" name="_shelby_brand_action" value="update"{if empty($entry[$selected_language].brands_id)} disabled{/if}><LABEL>Обновить</LABEL>
            <INPUT type="radio" name="_shelby_brand_action" value="new"{if empty($entry[$selected_language].brands_id)} checked{/if}><LABEL>Создать</LABEL>
          </div>
        </TD>
      </TR>
      
      <TR>
        <TD style="border-top:1px solid #333">Город:</TD>
      
        <TD style="border-top:1px solid #333">
          <INPUT type="text" size="5" name="common[cities_id]" id="cities_id" value="{$entry[$selected_language].cities_id}" onchange="createExpoCentersList(this.value);"> <INPUT type="button" onclick="objCitiesList.showPopUp();" value="Выбрать"> <SPAN id="cities_id_name">{$entry[$selected_language].city_name}</SPAN>
        </TD>
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

	 <tr>
	  <td>Вход на выставку:</td>
	  <td>
	   <SELECT name="common[is_free]">
	    <OPTION value=""{if is_null($entry[$selected_language].is_free)} selected="selected"{/if}>(Не выбрано)</OPTION>
	    <OPTION value="1"{if $entry[$selected_language].is_free === '1'} selected="selected"{/if}>Бесплатный</OPTION>
	    <OPTION value="0"{if $entry[$selected_language].is_free === '0'} selected="selected"{/if}>Платный</OPTION>
	   </SELECT>
	  </td>
	 </tr>
	 <tr>
	  <td>Стоимость входа:</td>
	  <td><input type="text" name="common[ticket_fee]" value="{$entry[$selected_language].ticket_fee}"/></td>
	 </tr>

      <TR>
        <TD colspan="2">
          <div>Дополнительная информация о событии:</div>
         
          <TABLE width="100%" style="border-collapse:collapse;">
          <TR>
            <TD style="width:34%;">Участников всего:</TD>
            <TD style="width:66%;"><INPUT type="text" name="common[partic_num]" size="10" value="{$entry[$selected_language].partic_num}" /></TD>
          </TR>
          
          <TR>
            <TD>Местных участников:</TD>
            <TD><INPUT type="text" name="common[local_partic_num]" size="10" value="{$entry[$selected_language].local_partic_num}" /></TD>
          </TR>
          
          <TR>
            <TD>Иностранных участников:</TD>
            <TD><INPUT type="text" name="common[foreign_partic_num]" size="10" value="{$entry[$selected_language].foreign_partic_num}" /></TD>
          </TR>
          
          <TR>
            <TD>Общая площадь:</TD>
            <TD><INPUT type="text" name="common[s_event_total]" size="10" value="{$entry[$selected_language].s_event_total}" /></TD>
          </TR>
          
          <TR>
            <TD>Посетителей всего:</TD>
            <TD><INPUT type="text" name="common[visitors_num]" size="10" value="{$entry[$selected_language].visitors_num}" /></TD>
          </TR>
          
          <TR>
            <TD>Местных посетителей:</TD>
            <TD><INPUT type="text" name="common[local_visitors_num]" size="10" value="{$entry[$selected_language].local_visitors_num}" /></TD>
          </TR>
          
          <TR>
            <TD>Иностранных посетителей:</TD>
            <TD><INPUT type="text" name="common[foreign_visitors_num]" size="10" value="{$entry[$selected_language].foreign_visitors_num}" /></TD>
          </TR>
          </TABLE>
        </TD>
      </TR>
      </TABLE>
    </fieldset>
    
    {foreach from=$list_languages item="lang"}
    {if !empty($entry[$lang.code])}
      <fieldset>
        <legend><strong>{$lang.name}</strong></legend>
        {include file="controllers/admin_ep_drafts/edit_lang_spec.tpl" lang=$lang.code}
      </fieldset>
    {/if}
    {/foreach}

  </TD>
</TR>
</TABLE>

<INPUT type="hidden" name="accept_type" id="accept_type" value="normal" />

<BR />

<CENTER>
  <INPUT type="submit" value="Принять по стандартной цене" />
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
  <INPUT type="button" value="Принять по повышенной цене" onclick="acceptHigh();" />
</CENTER>
</FORM>

<CENTER>

<FORM action="{getUrl add="1" action="update" type="update_draft"}" method="post">
  <INPUT type="hidden" name="status" value="-1" />
  Комментарии к черновику для оператора:<BR />
  <TEXTAREA name="comments" style="width:90%; height:100px;">{$entry[$selected_language].comments}</TEXTAREA><BR />
  <INPUT type="submit" value="Отклонить" />
</FORM>

</CENTER>
