<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>
var selected_banners = {$HMixed->toJson($entry.selected_banners)};
var selected_publishers = {$HMixed->toJson($entry.selected_publishers)};
{include file="controllers/admin_ep_banners_plans/js.tpl"}
{literal}

bannersObj.bannersListComplete = function() {
	$.each(selected_banners, function(i) {
		$("#banners_id_" + i + " option[value='" + selected_banners[i] + "']").attr('selected', 'selected');
	});
}

bannersObj.publishersListComplete = function() {
	$.each(selected_publishers, function(i) {
		$("#publishers_id option[value='" + selected_publishers[i] + "']").attr('selected', 'selected');
	});
}

$(document).ready(function(){
	bannersObj.getBannersList();
});
{/literal}
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="update"}" onsubmit="return bannersObj.validateForm();">

<h4>Редактируем план показов</h4>

<TABLE border="0" width="100%">
 <TR>
  <TD>Кампания:</TD>
  <TD><INPUT type="text" size="5" name="companies_id" id="companies_id" value="{$entry.companies_id}"/> <INPUT type="button" onclick="objCompaniesList.showPopUp();" value="Выбрать"/> <SPAN id="companies_id_name">{$entry.company_name}</SPAN></TD>
 </TR>
 <TR>
  <TD>Площадка:</TD>
  <TD>
   <SELECT name="places_id" id="places_id" onchange="bannersObj.getBannersList();">
    <OPTION value="-1">(Не выбрано)</OPTION>
    {foreach from=$list_places item="el"}
     <OPTION value="{$el.id}"{if $el.id==$entry.places_id} selected="selected"{/if}>{$el.name}</OPTION>
    {/foreach}
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD>Баннер:</TD>
  <TD>
   <TABLE id="banners_holder">
   {foreach from=$list_languages item="el"}
   <tr><td>{$el.name}:</td>
   <td><SELECT name="banners_id[{$el.id}]" id="banners_id_{$el.id}" disabled="disabled" style="width:300px;"></SELECT></td>
   </tr>
  {/foreach}
   </TABLE>
  </TD>
 </TR>
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="80" name="name" value="{$entry.name|escape:"html"}"></TD>
 </TR>
 <TR>
  <TD>Ссылка: </TD>
  <TD><INPUT type="text" size="80" name="url" value="{$entry.url}"></TD>
 </TR>
 <TR>
  <TD>Даты показа: </TD>
  <TD>
   с <INPUT type="text" size="12" name="date_from" id="date_from" value="{$entry.date_from}"/> &nbsp;
   по <INPUT type="text" size="12" name="date_to" id="date_to" value="{$entry.date_to}"/>
  </TD>
 </TR>
 <TR>
  <TD>Приоритет: </TD>
  <TD><INPUT type="text" name="priority" id="priority" size="4" {if $entry.priority==65000}disabled="disabled"{else}value="{$entry.priority}"{/if} maxlength="3" />  (1-999) заглушка <input type="checkbox" onclick="bannersObj.toggleDummy(this.checked);" {if $entry.priority==65000}checked="checked"{/if} /></TD>
 </TR>
</TABLE>

<TABLE width="100%">
 <TR>
  <TD width="25%">
   Модули:<br />
   <SELECT name="modules_id[]" multiple="multiple" id="modules_id" style="height:200px; width:100%;">
   {foreach from=$list_modules item="el"}
     <OPTION value="{$el.id}"{if isset($entry.selected_modules[$el.id])} selected="selected"{/if}>{$el.name}</OPTION>
   {/foreach}
   </SELECT>
  </TD>
  <TD width="50%">
   Категории:<br />
   <SELECT name="categories_id[]" multiple="multiple" id="categories_id" style="height:200px; width:100%;">
   {foreach from=$list_categories item="el"}
     <OPTION value="{$el.id}"{if isset($entry.selected_categories[$el.id])} selected="selected"{/if}>{$el.name}</OPTION>
   {/foreach}
   </SELECT>
  </TD>
  <TD width="25%">
   Издатели:<br />
   <SELECT name="publishers_id[]" id="publishers_id" multiple="multiple" style="height:200px; width:100%;"></SELECT>
  </TD>
 </TR>
</TABLE>

<CENTER><INPUT type="submit" value="Изменить"></CENTER>

</FORM>

<P><a href="{getUrl add="1" action="list"}">Вернуться к списку</a></P>