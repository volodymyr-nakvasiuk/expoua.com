<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>
{include file="controllers/admin_ep_banners_plans/js.tpl"}
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="insert"}" onsubmit="return bannersObj.validateForm();">

<h4>Добавляем новый план показов</h4>

<TABLE border="0" width="100%">
 <TR>
  <TD>Кампания:</TD>
  <TD><INPUT type="text" size="5" name="companies_id" id="companies_id" onchange="bannersObj.getBannersList();" /> <INPUT type="button" onclick="objCompaniesList.showPopUp();" value="Выбрать"/> <SPAN id="companies_id_name"></SPAN></TD>
 </TR>
 <TR>
  <TD>Площадка:</TD>
  <TD>
   <SELECT name="places_id" id="places_id" onchange="bannersObj.getBannersList();">
    <OPTION value="-1">(Не выбрано)</OPTION>
    {foreach from=$list_places item="el"}
     <OPTION value="{$el.id}">{$el.name}</OPTION>
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
   <td><SELECT name="banners_id[{$el.id}]" disabled="disabled" style="width:300px;"></SELECT></td>
   </tr>
  {/foreach}
   </TABLE>
  </TD>
 </TR>
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="80" name="name"></TD>
 </TR>
 <TR>
  <TD>Ссылка: </TD>
  <TD><INPUT type="text" size="80" name="url"></TD>
 </TR>
 <TR>
  <TD>Даты показа: </TD>
  <TD>
   с <INPUT type="text" size="12" name="date_from" id="date_from"/> &nbsp;
   по <INPUT type="text" size="12" name="date_to" id="date_to"/>
  </TD>
 </TR>
 <TR>
  <TD>Приоритет: </TD>
  <TD><INPUT type="text" name="priority" id="priority" size="4" value="1" maxlength="3" /> (1-999) заглушка <input type="checkbox" onclick="bannersObj.toggleDummy(this.checked);" /></TD>
 </TR>
</TABLE>

<TABLE border="0" width="100%">
 <TR>
  <TD width="25%">
   Модули:<br />
   <SELECT name="modules_id[]" multiple="multiple" id="modules_id" style="height:200px; width:100%;">
   {foreach from=$list_modules item="el"}
     <OPTION value="{$el.id}">{$el.name}</OPTION>
   {/foreach}
   </SELECT>
  </TD>
  <TD width="50%">
   Категории:<br />
   <SELECT name="categories_id[]" multiple="multiple" id="categories_id" style="height:200px; width:100%;">
   {foreach from=$list_categories item="el"}
     <OPTION value="{$el.id}">{$el.name}</OPTION>
   {/foreach}
   </SELECT>
  </TD>
  <TD width="25%">
   Издатели:<br />
   <SELECT name="publishers_id[]" id="publishers_id" multiple="multiple" style="height:200px; width:100%;">
   </SELECT>
  </TD>
 </TR>
</TABLE>

<CENTER><INPUT type="submit" value="Добавить"></CENTER>

</FORM>

<P><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></P>