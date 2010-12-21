<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT type="text/javascript">
objEventsList = Shelby_Backend.ListHelper.cloneObject('objEventsList');

objEventsList.columns = new Array(new Array('date_from', '{#captionStartDate#}'), new Array('name', '{#captionName#}'), new Array('city_name', '{#captionCity#}'));
objEventsList.returnFieldId = 'events_id';
objEventsList.feedUrl = '{getUrl controller="sab_jsonfeeds" action="events" sort="date_from:ASC"}';
objEventsList.persistentFilter = 'active:1;date_to>{$smarty.now|date_format:"%Y-%m-%d"}';
objEventsList.writeForm();

{literal}
objEventsList.callbackUser = function(entry) {
	var text = '';

	text = '<div id="companies_to_events' + entry.id + '" style="border-bottom:1px solid #cccccc; float:left;">' + '<div style="width:700px; float:left;">' +
	'<input type="hidden" name="companies_to_events[' + entry.id + '][id]" value="' + entry.id + '">' +
	entry.date_from + ": " + entry.name + " - " + entry.country_name + ", " + entry.city_name +
	'; {/literal}{#captionStandNo#}{literal}: <input type="text" size="10" name="companies_to_events[' + entry.id + '][stand_num]">' +
	'</div> <input type="button" value="{/literal}{#deleteAction#}{literal}" onClick="$(\'#companies_to_events' + entry.id + '\').remove();"></div>';

	$("#events_list").append(text);
}
{/literal}
</SCRIPT>

<h4>{#hdrChooseExhibitions#}</h4>

<FORM method="post" action="{getUrl add="1" action="update" id="0"}">

<INPUT type="button" onclick="objEventsList.showPopUp();" value="{#actionAddExhibition#}"><BR /><BR />

   <P id="events_list">
   {foreach from=$entry.list_events item="el"}
    <div id="companies_to_events{$el.id}" style="border-bottom:1px solid #cccccc; float:left;">
     <div style="width:700px; float:left;">
      <input type="hidden" name="companies_to_events[{$el.id}][id]" value="{$el.id}">
      {$el.date_from}: {$el.name} - {$el.country_name}, {$el.city_name};
      {#captionStandNo#}: <input type="text" size="10" name="companies_to_events[{$el.id}][stand_num]" value="{$el.stand_num}">
     </div>
     <input type="button" value="{#deleteAction#}" onClick="$('#companies_to_events{$el.id}').remove();">
    </div>
   {/foreach}
   </P>

   <DIV style="clear:both; padding:10px;"></DIV>

   <INPUT type="submit" value="{#saveAction#}">

</FORM>