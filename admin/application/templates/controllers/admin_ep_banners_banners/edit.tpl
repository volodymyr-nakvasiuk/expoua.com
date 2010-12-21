<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>
objAdvertisersList = Shelby_Backend.ListHelper.cloneObject('objAdvertisersList');
objAdvertisersList.columns = new Array(new Array('id', 'Id'), new Array('name', 'Название'));
objAdvertisersList.returnFieldId = 'advertisers_id';
objAdvertisersList.feedUrl = '{getUrl controller="admin_ep_banners_advertisers" action="list" feed="json"}';
objAdvertisersList.writeForm();

objEventsList = Shelby_Backend.ListHelper.cloneObject('objEventsList');
objEventsList.columns = new Array(new Array('id', 'Id'), new Array('date_from', 'Дата'), new Array('name', 'Название'), new Array('city_name', 'Город'));
objEventsList.returnFieldId = 'events_id';
objEventsList.feedUrl = '{getUrl controller="admin_ep_brandsevents" action="list" feed="json" sort="date_from:DESC"}';
objEventsList.writeForm();

{literal}
objEventsList.callbackUser = function(json) {
	$("#events_id_name").html(json.date_from + ": " + json.name + " (" + json.country_name + "/" + json.city_name + ")");
}
{/literal}
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="update"}" enctype="multipart/form-data">

<h4>Редактируем баннер</h4>

<TABLE border="0" width="100%">
 <TR>
  <TD>Рекламодатель:</TD>
  <TD><INPUT type="text" size="5" name="advertisers_id" id="advertisers_id" value="{$entry.advertisers_id}"/> <INPUT type="button" onclick="objAdvertisersList.showPopUp();" value="Выбрать"/> <SPAN id="advertisers_id_name"></SPAN></TD>
 </TR>
 <TR>
  <TD>Тип баннера: </TD>
  <TD>
   <INPUT type="hidden" name="types_id" value="{$entry.types_id}" />
   {$list_types[$entry.types_id].name}
  </TD>
 </TR>
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="80" name="name" value="{$entry.name|escape:"html"}"></TD>
 </TR>
 <TR>
  <TD>Описание:</TD>
  <TD><TEXTAREA name="description" style="width:95%; height:100px;">{$entry.description|escape:"html"}</TEXTAREA></TD>
 </TR>
</TABLE>

{assign var="banner_type" value=$list_types[$entry.types_id].media}

<table border="0" align="center">
{if $banner_type=='image' || $banner_type=='flash'}

  <tr><td>Файл: </td><td><input type="file" name="file"/></td></tr>
  <tr><td>Alt: </td><td><input type="text" name="file_alt" size="20" value="{$entry.file_alt}" /></td></tr>
  <tr><td colspan="2">
  {if $banner_type=='flash'}

<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
			 codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
			 WIDTH="200" HEIGHT="20" id="fkash_top">
			 <PARAM NAME=movie VALUE="/data/images/banners/{$entry.file_name}"> <PARAM NAME=menu VALUE=false> <PARAM NAME=quality VALUE=high> <EMBED src="/data/images/banners/{$entry.file_name}" menu="false" quality="high" WIDTH="200" HEIGHT="200" NAME="fkash_top" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>
</OBJECT>

  {else}
   <IMG src="/data/images/banners/{$entry.file_name}" />
  {/if}
  </td></tr>

{elseif $banner_type=="text"}

  <tr><td>Текст: </td><td><textarea style="width:300px; height:100px;" name="text_content">{$entry.text_content|escape:"html"}</textarea></td></tr>

{elseif $banner_type=='pline'}

  <tr><td>Выставка: </td>
  <td>

   <SCRIPT type="text/javascript">
   var url_event = '{getUrl controller="admin_ep_events" action="view" id=$entry.pline_events_id feed="json"}';
   {literal}
    $(document).ready(function(){
    	$.getJSON(url_event, function(json) {
    		$("#events_id_name").html(json.entry.date_from + ": " + json.entry.brand_name + " (" + json.entry.city_name + ")");
    	});
    });
   {/literal}
   </SCRIPT>

   <input type="text" name="pline_events_id" id="events_id" size="5" value="{$entry.pline_events_id}"/> <INPUT type="button" onclick="objEventsList.showPopUp();" value="Выбрать"/> <SPAN id="events_id_name"></SPAN>
  </td>
  </tr>

{/if}
</TABLE>

<br />

<CENTER><INPUT type="submit" value="Изменить"></CENTER>

</FORM>

<P><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></P>