{include file="common/contentVisualEdit.tpl" textarea="thematic_sections,description"}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT type="text/javascript" language="javascript">

objOrganizersList = Shelby_Backend.ListHelper.cloneObject('objOrganizersList');

objOrganizersList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('city_name', 'Город'));
objOrganizersList.returnFieldId = 'brand_organizers_id';
objOrganizersList.feedUrl = '{getUrl controller="sab_jsonfeeds" action="organizers"}';
objOrganizersList.writeForm();

{literal}

function createExpoCentersList(id) {
	var url = '{/literal}{getUrl controller="sab_jsonfeeds" action="expocenters"}{literal}search/cities_id:' + id + '/';

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

$(document).ready(function(){

	$('#date_from').datepicker();
	$('#date_to').datepicker();

	createExpoCentersList({/literal}{$entry.cities_id}{literal});
	{/literal}{if is_null($entry.brands_id)}createBrendCategories();{/if}{literal}

});
{/literal}
</SCRIPT>


<H4>Редактируем черновик</H4>

<FORM action="{getUrl add="1" action="update"}" method="post">

<TABLE border="0" width="100%" style="border-collapse:collapse;">
<TR valign="top">
  <td width="49%">
    <fieldset>
      <legend>Данные о выставке</legend>

      <TABLE border="0" width="100%" style="border-collapse:collapse;">
      <TR>
        <TD>Бренд:</TD>
        {if is_null($entry.brands_id)}
        <TD>
          <TABLE width="100%">
          <TR>
            <TD>Организатор: </TD>
            <TD><input type="text" size="5" name="brand_organizers_id" id="brand_organizers_id" value="{$entry.brand_organizers_id}"> <INPUT type="button" onclick="objOrganizersList.showPopUp();" value="Выбрать"> <SPAN id="brand_organizers_id_name">{$entry.organizer_name}</SPAN></TD>
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
        <TD><INPUT type="text" size="5" name="brands_id" value="{$entry.brands_id}" readonly /> <SPAN id="brands_id_name">{$entry.brand_name}<BR />{$entry.brand_name_extended}</SPAN></TD>
        {/if}
      </TR>
       
      <TR>
        <TD>Город:</TD>
        <TD><INPUT type="text" size="5" name="cities_id" value="{$entry.cities_id}" readonly /> <SPAN id="cities_id_name">{$entry.city_name}</SPAN></TD>
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
        <TD>с <INPUT type="text" size="12" name="date_from" id="date_from" onchange="$('#date_to').val(this.value);" value="{$entry.date_from}"> по <INPUT type="text" size="12" name="date_to" id="date_to" value="{$entry.date_to}"></TD>
      </TR>
      
      <TR>
        <TD>Период проведения:</TD>
        <TD>
          <SELECT name="periods_id">
            <OPTION value="">(Не выбрано)</OPTION>
            {foreach from=$list_periods item="el"}
              <OPTION value="{$el.id}"{if $entry.periods_id==$el.id} selected{/if}>{$el.name}</OPTION>
            {/foreach}
          </SELECT>
        </TD>
      </TR>
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
        <TD><INPUT type="text" name="partic_num" size="10" value="{$entry.partic_num}"></TD>
      </TR>
      <TR>
        <TD>Местных участников:</TD>
        <TD><INPUT type="text" name="local_partic_num" size="10" value="{$entry.local_partic_num}"></TD>
      </TR>
      <TR>
        <TD>Иностранных участников:</TD>
        <TD><INPUT type="text" name="foreign_partic_num" size="10" value="{$entry.foreign_partic_num}"></TD>
      </TR>
      <TR>
        <TD>Общая площадь:</TD>
        <TD><INPUT type="text" name="s_event_total" size="10" value="{$entry.s_event_total}"></TD>
      </TR>
      <TR>
        <TD>Посетителей всего:</TD>
        <TD><INPUT type="text" name="visitors_num" size="10" value="{$entry.visitors_num}"></TD>
      </TR>
      <TR>
        <TD>Местных посетителей:</TD>
        <TD><INPUT type="text" name="local_visitors_num" size="10" value="{$entry.local_visitors_num}"></TD>
      </TR>
      <TR>
        <TD>Иностранных посетителей:</TD>
        <TD><INPUT type="text" name="foreign_visitors_num" size="10" value="{$entry.foreign_visitors_num}"></TD>
      </TR>
      </TABLE>
    </fieldset>
  </td>
</tr>
</table>  




<TABLE border="0" width="100%" style="border-collapse:collapse;">
<TR valign="top">
<!-- ---------------------------------------------------------------------------------------------- -->
  <td>
    <fieldset>
      <legend>Язык - {$selected_language}</legend>

      <TABLE border="0" width="100%" style="border-collapse:collapse;">
      <TR>
        <TD>Время работы:<br />
          <TEXTAREA name="work_time" style="width:99%; height:50px;">{$entry.work_time}</TEXTAREA>
        </TD>
      </TR>
      
      <TR>
      
      <TR>
        <TD>Описание:<BR />
          <TEXTAREA name="description" id="description" style="width:99%; height:150px;">{$entry.description|escape:"html"}</TEXTAREA>
        </TD>
      </TR>
      
      <TR>
        <TD>Тематически секции:<BR />
          <TEXTAREA name="thematic_sections" id="thematic_sections" style="width:99%; height:200px;">{$entry.thematic_sections|escape:"html"}</TEXTAREA>
        </TD>
      </TR>
      
      <TR>
        <TD valign="top">
          <div>Контакты проектной группы:</div>
          
          <TABLE style="border-collapse:collapse; width:100%">
          <TR>
           <TD>Email: </TD>
           <TD><INPUT type="text" name="email" id="email" size="25" value="{$entry.email}"></TD>
          </TR>
          <TR>
           <TD>Адрес сайта: </TD>
           <TD><INPUT type="text" name="web_address" id="web_address" size="25" value="{$entry.web_address}"></TD>
          </TR>
          <TR>
           <TD>Телефон: </TD>
           <TD><INPUT type="text" name="phone" id="phone" size="25" value="{$entry.phone}"></TD>
          </TR>
          <TR>
           <TD>Факс: </TD>
           <TD><INPUT type="text" name="fax" id="fax" size="25" value="{$entry.fax}"></TD>
          </TR>
          <TR>
           <TD>Контактное лицо: </TD>
           <TD><INPUT type="text" name="cont_pers_name" size="25" value="{$entry.cont_pers_name}"></TD>
          </TR>
          <TR>
           <TD>Телефон КЛ: </TD>
           <TD><INPUT type="text" name="cont_pers_phone" size="25" value="{$entry.cont_pers_phone}"></TD>
          </TR>
          <TR>
           <TD>Email КЛ: </TD>
           <TD><INPUT type="text" name="cont_pers_email" size="25" value="{$entry.cont_pers_email}"></TD>
          </TR>
          </TABLE>
        </TD>
      </TR>
      </TABLE>
    </fieldset>
  </td>
  <!-- ---------------------------------------------------------------------------------------------- -->
</tr>
</table>

  
<BR />
<CENTER>
Отправить черновик координатору: <INPUT type="checkbox" name="status" value="1" {if $entry.status==1}checked="checked"{/if} /><BR />
 Комментарии к черновику для координатора:<BR />
 <TEXTAREA name="comments" style="width:90%; height:100px;">{$entry.comments}</TEXTAREA><BR />
 <INPUT type="submit" value="Обновить" />
</CENTER>

</FORM>

{if $smarty.get.debug}{debug}{/if}