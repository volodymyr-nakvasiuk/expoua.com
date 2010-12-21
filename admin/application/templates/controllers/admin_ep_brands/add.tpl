<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>

objOrganizersList = Shelby_Backend.ListHelper.cloneObject('objOrganizersList');

objOrganizersList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('city_name', 'Город'));
objOrganizersList.returnFieldId = 'organizers_id';
objOrganizersList.feedUrl = '{getUrl controller="admin_ep_organizers" action="list" feed="json"}';
objOrganizersList.writeForm();

{literal}
objOrganizersList.callbackUser = function(entry) {
	var url = '{/literal}{getUrl controller="admin_ep_organizers" action="view" feed="json"}{literal}id/' + entry.id;

	$.getJSON(url, function(json) {
		$("#email_requests").val(json.entry.email);
	});
}
{/literal}
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="insert"}">

<h4>Добавляем бренд</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Организатор:</TD>
  <TD><INPUT type="text" size="5" name="common[organizers_id]" id="organizers_id"> <INPUT type="button" onclick="objOrganizersList.showPopUp();" value="Выбрать"> <SPAN id="organizers_id_name"></SPAN></TD>
 </TR>
 <TR>
  <TD>Основная категория:</TD>
  <TD>
   <SELECT name="common[brands_categories_id]">
    {foreach from=$list_categories item="cat"}
     <OPTION value="{$cat.id}">{$cat.name}</OPTION>
    {/foreach}
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD>Дополнительные категории:</TD>
  <TD>
  <DIV style="overflow:auto; height:350px;">
  {strip}
   {foreach from=$list_categories item="cat"}
    <INPUT type="checkbox" name="cat[{$cat.id}]" id="cat{$cat.id}" value="{$cat.id}" /> <b>{$cat.name}</b><BR />
    {foreach from=$cat.subcats item="subcat"}
     &nbsp; &nbsp; <INPUT type="checkbox" name="subcat[{$subcat.id}]" value="{$subcat.id}" onclick="$('#cat{$cat.id}').attr('checked', 'checked');" /> {$subcat.name}<BR />
    {/foreach}
   {/foreach}
  {/strip}
  </DIV>
  </TD>
 </TR>
 <TR>
  <TD>Email для запросов:</TD>
  <TD><INPUT type="text" size="20" name="common[email_requests]" id="email_requests" /></TD>
 </TR>
</TABLE>

{foreach from=$list_languages item="lang"}
<h3 align="center">{$lang.name}</h3>
<table border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="80" name="{$lang.code}[name]"></TD>
 </TR>
 <TR>
  <TD colspan="2">Расширенное название:<BR />
   <TEXTAREA name="{$lang.code}[name_extended]" id="name_extended" style="width:95%; height:50px;"></TEXTAREA>
  </TD>
 </TR>
</table>
{/foreach}

<br/>

 <center><INPUT type="submit" value="Добавить"></center>

</FORM>