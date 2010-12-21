{strip}{assign var="ve_textareas" value=""}
{foreach from=$list_languages item="lang" name="lang_fe"}
{assign var="ve_textareas" value="`$ve_textareas`thematic_sections_`$lang.code`,description_`$lang.code`"}
{if !$smarty.foreach.lang_fe.last}{assign var="ve_textareas" value="`$ve_textareas`,"}{/if}
{/foreach}{/strip}
{include file="common/orgContentVisualEdit.tpl" textarea=$ve_textareas}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT type="text/javascript" language="javascript">

objOrganizersList = Shelby_Backend.ListHelper.cloneObject('objOrganizersList');

objOrganizersList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('city_name', 'Город'));
objOrganizersList.returnFieldId = 'brand_organizers_id';
objOrganizersList.feedUrl = '{getUrl controller="sab_jsonfeeds" action="organizers"}';
objOrganizersList.writeForm();

{literal}

function createExpoCentersList(id) {
	var url = '{/literal}{getUrl controller="sab_jsonfeeds" action="expocenters" results="1000" sort="name:ASC"}{literal}search/cities_id:' + id + '/';

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

function createBrendCategories() {
	var url = '{/literal}{getUrl controller="sab_jsonfeeds" action="brandscategories"}{literal}';

	$.getJSON(url, function(json) {
		var tmp = '<SELECT name="brand_categories_id">';
		$.each(json.list.data, function(i) {
			tmp += '<option value="' + json.list.data[i].id + '"';
			if (json.list.data[i].id==parseInt({/literal}{$entry.brand_categories_id}{literal})) {
				tmp += ' selected';
			}
			tmp += '>' + json.list.data[i].name + '</option>';

		});
		tmp += "</SELECT>";
		$("#brand_categories_holder").html(tmp);
	});
}

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
$(document).ready(function(){

	$('#date_from').datepicker();
	$('#date_to').datepicker();

	createExpoCentersList({/literal}{$entry[$selected_language].cities_id}{literal});
	{/literal}{if is_null($entry[$selected_language].brands_id)}createBrendCategories();{/if}{literal}

});
{/literal}
</SCRIPT>


<H4>Редактируем черновик</H4>

<FORM action="{getUrl add="1" action="update"}" method="post" onsubmit="return checkLength();">

<TABLE border="0" width="100%" style="border-collapse:collapse;">
<TR valign="top">
  <td width="49%">
    <fieldset>
      <legend>Данные о выставке</legend>

      <TABLE border="0" width="100%" style="border-collapse:collapse;">
      <TR>
        <TD>Бренд:</TD>
        {if is_null($entry[$selected_language].brands_id)}
        <TD>
          <TABLE width="100%">
          <TR>
            <TD>Организатор: </TD>
            <TD><input type="text" size="5" name="common[brand_organizers_id]" id="brand_organizers_id" value="{$entry[$selected_language].brand_organizers_id}"> <INPUT type="button" onclick="objOrganizersList.showPopUp();" value="Выбрать"> <SPAN id="brand_organizers_id_name">{$entry.organizer_name}</SPAN></TD>
          </TR>
          <TR>
            <TD>Название:</TD>
            <TD><input type="text" size="60" name="brand_name_new" id="brand_name_new" value="{$entry.brand_name_new}" /></TD>
          </TR>
          <TR>
            <TD valign="top">Расширенное название:</TD>
            <TD><textarea name="brand_name_extended_new" id="brand_name_extended_new" style="width:90%; height:40px;">{$entry.brand_name_extended_new}</textarea></TD>
          </TR>
          
          <TR>
            <TD>Категория:</TD>
            <TD>
              <SELECT name="brand_categories_id" id="brand_categories_id">
              {foreach from=$list_brand_categories item="el"}
                <OPTION value="{$el.id}">{$el.name}</OPTION>
              {/foreach}
              </SELECT>
            </TD>
          </TR>
          </TABLE>
        </TD>
        {else}
        <TD><INPUT type="text" size="5" name="common[brands_id]" value="{$entry[$selected_language].brands_id}" readonly /> <SPAN id="brands_id_name">{$entry[$selected_language].brand_name}<BR />{$entry[$selected_language].brand_name_extended}</SPAN></TD>
        {/if}
      </TR>
       
      <TR>
        <TD>Город:</TD>
        <TD><INPUT type="text" size="5" name="common[cities_id]" value="{$entry[$selected_language].cities_id}" readonly /> <SPAN id="cities_id_name">{$entry[$selected_language].city_name}</SPAN></TD>
      </TR>
      
      <TR>
        <TD>Выставочный центр:</TD>
        <TD id="expocenters_id"></TD>
      </TR>
      
      <TR>
        <TD>Номер события:</TD>
        <TD><INPUT type="text" size="5" name="common[number]" value="{$entry[$selected_language].number}"></TD>
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
      
      </table>
    </fieldset>
  </td>
  
  <td width="2%">&nbsp;</td>
  
  <td width="49%">
    <fieldset>
      <legend>Дополнительная информация о событии</legend>

      <TABLE style="border-collapse:collapse;">
      <TR>
        <TD>Участников всего:</TD>
        <TD><INPUT type="text" name="common[partic_num]" size="10" value="{$entry[$selected_language].partic_num}"></TD>
      </TR>
      <TR>
        <TD>Местных участников:</TD>
        <TD><INPUT type="text" name="common[local_partic_num]" size="10" value="{$entry[$selected_language].local_partic_num}"></TD>
      </TR>
      <TR>
        <TD>Иностранных участников:</TD>
        <TD><INPUT type="text" name="common[foreign_partic_num]" size="10" value="{$entry[$selected_language].foreign_partic_num}"></TD>
      </TR>
      <TR>
        <TD>Общая площадь:</TD>
        <TD><INPUT type="text" name="common[s_event_total]" size="10" value="{$entry[$selected_language].s_event_total}"></TD>
      </TR>
      <TR>
        <TD>Посетителей всего:</TD>
        <TD><INPUT type="text" name="common[visitors_num]" size="10" value="{$entry[$selected_language].visitors_num}"></TD>
      </TR>
      <TR>
        <TD>Местных посетителей:</TD>
        <TD><INPUT type="text" name="common[local_visitors_num]" size="10" value="{$entry[$selected_language].local_visitors_num}"></TD>
      </TR>
      <TR>
        <TD>Иностранных посетителей:</TD>
        <TD><INPUT type="text" name="common[foreign_visitors_num]" size="10" value="{$entry[$selected_language].foreign_visitors_num}"></TD>
      </TR>
      </TABLE>
    </fieldset>
  </td>
</tr>
</table>  




<TABLE border="0" width="100%" style="border-collapse:collapse;">
<TR valign="top">
{foreach from=$list_languages item=lang}
  <td>
    <fieldset>
      <legend>Язык - {$lang.name}</legend>

      <TABLE border="0" width="100%" style="border-collapse:collapse;">
      <TR>
        <TD>Время работы:<br />
          <TEXTAREA name="{$lang.code}[work_time]" style="width:99%; height:50px;">{$entry[$lang.code].work_time}</TEXTAREA>
        </TD>
      </TR>
      
      <TR>
        <TD><a name="anchor_description_{$lang}""><!-- --></a>Описание:<BR />
          <TEXTAREA name="{$lang.code}[description]" id="description_{$lang.code}" style="width:99%; height:150px;">{$entry[$lang.code].description|escape:"html"}</TEXTAREA>
        </TD>
      </TR>
      
      <TR>
        <TD>Тематически секции:<BR />
          <TEXTAREA name="{$lang.code}[thematic_sections]" id="thematic_sections_{$lang.code}" style="width:99%; height:200px;">{$entry[$lang.code].thematic_sections|escape:"html"}</TEXTAREA>
        </TD>
      </TR>
      
      <TR>
        <TD valign="top">
          <div>Контакты проектной группы:</div>
          
          <TABLE style="border-collapse:collapse; width:100%">
          <TR>
           <TD>Email: </TD>
           <TD><INPUT type="text" name="{$lang.code}[email]" id="email_{$lang.code}" size="25" value="{$entry[$lang.code].email}"></TD>
          </TR>
          <TR>
           <TD>Адрес сайта: </TD>
           <TD><INPUT type="text" name="{$lang.code}[web_address]" id="web_address_{$lang.code}" size="25" value="{$entry[$lang.code].web_address}"></TD>
          </TR>
          <TR>
           <TD>Телефон: </TD>
           <TD><INPUT type="text" name="{$lang.code}[phone]" id="phone_{$lang.code}" size="25" value="{$entry[$lang.code].phone}"></TD>
          </TR>
          <TR>
           <TD>Факс: </TD>
           <TD><INPUT type="text" name="{$lang.code}[fax]" id="fax_{$lang.code}" size="25" value="{$entry[$lang.code].fax}"></TD>
          </TR>
          <TR>
           <TD>Контактное лицо: </TD>
           <TD><INPUT type="text" name="{$lang.code}[cont_pers_name]" size="25" value="{$entry[$lang.code].cont_pers_name}"></TD>
          </TR>
          <TR>
           <TD>Телефон КЛ: </TD>
           <TD><INPUT type="text" name="{$lang.code}[cont_pers_phone]" size="25" value="{$entry[$lang.code].cont_pers_phone}"></TD>
          </TR>
          <TR>
           <TD>Email КЛ: </TD>
           <TD><INPUT type="text" name="{$lang.code}[cont_pers_email]" size="25" value="{$entry[$lang.code].cont_pers_email}"></TD>
          </TR>
          </TABLE>
        </TD>
      </TR>
      </TABLE>
    </fieldset>
  </td>
  {/foreach}
</tr>
</table>

  
<BR />
<CENTER>
Отправить черновик координатору: <INPUT type="checkbox" name="common[status]" value="1" {if $entry[$selected_language].status==1}checked="checked"{/if} /><BR />
 Комментарии к черновику для координатора:<BR />
 <TEXTAREA name="common[comments]" style="width:90%; height:100px;">{$entry[$selected_language].comments}</TEXTAREA><BR />
 <INPUT type="submit" value="Обновить" />
</CENTER>

</FORM>