{strip}{assign var="ve_textareas" value=""}
{foreach from=$list_languages item="lang" name="lang_fe"}
{assign var="ve_textareas" value="`$ve_textareas`thematic_sections_`$lang.code`,description_`$lang.code`"}
{if !$smarty.foreach.lang_fe.last}{assign var="ve_textareas" value="`$ve_textareas`,"}{/if}
{/foreach}{/strip}
{include file="common/orgContentVisualEdit.tpl" textarea=$ve_textareas}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT type="text/javascript" language="javascript">

objOrganizersList = Shelby_Backend.ListHelper.cloneObject('objOrganizersList');
objCitiesList = Shelby_Backend.ListHelper.cloneObject('objCitiesList');

objOrganizersList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('city_name', 'Город'));
objOrganizersList.returnFieldId = 'brand_organizers_id';
objOrganizersList.feedUrl = '{getUrl controller="sab_jsonfeeds" action="organizers"}';
objOrganizersList.writeForm();

objCitiesList.columns = new Array(new Array('name', 'Город'), new Array('country_name', 'Страна'));
objCitiesList.returnFieldId = 'cities_id';
objCitiesList.feedUrl = '{getUrl controller="sab_jsonfeeds" action="cities" sort="name:ASC"}';
objCitiesList.writeForm();

{literal}

objCitiesList.callbackUser = function(entry) {
	createExpoCentersList(entry.id);
}

function createExpoCentersList(id) {
	var url = '{/literal}{getUrl controller="sab_jsonfeeds" action="expocenters"}{literal}search/cities_id:' + id + '/';

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
		var tmp = '<SELECT name="common[brand_categories_id]">';
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

$(document).ready(function(){

	$('#date_from').datepicker();
	$('#date_to').datepicker();

	createExpoCentersList({/literal}{$entry[$selected_language].cities_id}{literal});
	{/literal}{if is_null($entry[$selected_language].brands_id)}createBrendCategories();{/if}{literal}

});

function preview() {
	var insertAction = '{/literal}{getUrl add="1" action="update"}{literal}';
	var previewAction = '{/literal}{getUrl controller="sab_organizer_drafts" action="preview"}{literal}';

	$('#mainform').attr('action', previewAction);
	$('#mainform').attr('target', 'draft_preview');

	$("#brand_categories_name").val($("select[@id='brand_categories_id'] :selected").text());
	$("#city_name").val($("#cities_id_name").text());
	$("#expocenter_name").val($("select[@id='expocenters_id'] :selected").text());
	$("#period_name").val($("select[@id='periods_id'] :selected").text());

	$('#mainform').submit();

	$('#mainform').attr('action', insertAction);
	$('#mainform').attr('target', '_self');
}

{/literal}
</SCRIPT>


<!-- <H4>{#edit__exh#}</H4> -->

<p style="border:1px solid #900; background:#FDEBEA; padding:10px;">{#disclaimer#}</p>

<FORM action="{getUrl add="1" action="update"}" method="post" id="mainform">
<TABLE border="0" width="100%" style="border-collapse:collapse;" cellpadding="5">
  {*if is_null($entry[$selected_language].brands_id)*}
{*<table width="400" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="wb">Организатор: <SPAN id="brand_organizers_id_name">{$entry[$selected_language].organizer_name}</SPAN> <input type="hidden" name="common[brand_organizers_id]" id="brand_organizers_id" value="{$entry[$selected_language].brand_organizers_id}"> <INPUT type="button" onclick="objOrganizersList.showPopUp();" value="Выбрать"></td>
	</tr>
</table>
<BR />
*}
 {foreach from=$list_languages item="lang"}
 <TR>
  <TD width="200" class="wb">{#exhName#} ({$lang.name}):</TD>
  <TD><input type="text" size="60" name="{$lang.code}[brand_name_new]" id="brand_name_new_{$lang.code}" value="{$entry[$lang.code].brand_name}" /></TD>
 </TR>
 {/foreach}
 {foreach from=$list_languages item="lang"}
 <TR>
  <TD width="200" valign="top" class="wb">{#axhfullName#} ({$lang.name}):</TD>
  <TD><textarea name="{$lang.code}[brand_name_extended_new]" id="brand_name_extended_new_{$lang.code}" style="width:90%; height:40px;">{$entry[$lang.code].brand_name_extended}</textarea></TD>
 </TR>
 {/foreach}

  {*else}
 <TR>
  <TD colspan="2"><INPUT type="hidden" name="common[brands_id]" value="{$entry[$selected_language].brands_id}" readonly /> <SPAN id="brands_id_name">{$entry[$selected_language].brand_name}<BR />{$entry[$selected_language].brand_name_extended}</SPAN></TD>
 </TR>
  {/if*}
 <TR>
  <TD class="wb">{#exhMainTheme#}:</TD>
  <TD>
   <SELECT name="common[brand_categories_id]" id="brand_categories_id">
   {foreach from=$list_brand_categories item="el"}
    <OPTION value="{$el.id}">{$el.name}</OPTION>
   {/foreach}
   </SELECT>  </TD>
 </TR>
 <TR>
 <TR>
  <TD class="wb">{#city#}:</TD>
  <TD><INPUT type="hidden" name="common[cities_id]" value="{$entry[$selected_language].cities_id}" readonly /> <SPAN id="cities_id_name">{$entry[$selected_language].city_name}</SPAN>&nbsp;  <INPUT type="button" onclick="objCitiesList.showPopUp();" value="Выбрать"></TD>
 </TR>
 <TR>
  <TD class="wb">{#exhCenter#}:</TD>
<TD id="expocenters_id">({#selectCityFirst#})</TD>
 </TR>
  <TD class="wb">{#datesExh#}:</TD>
  <TD>с <INPUT type="text" size="12" name="common[date_from]" id="date_from" onchange="$('#date_to').val(this.value);" value="{$entry[$selected_language].date_from}"> по <INPUT type="text" size="12" name="common[date_to]" id="date_to" value="{$entry[$selected_language].date_to}"></TD>
 </TR>
 <TR>
  <TD class="wb">{#periodEvent#}:</TD>
  <TD>
   <SELECT name="common[periods_id]">
    <OPTION value="">({#notSelect#})</OPTION>
    {foreach from=$list_periods item="el"}
     <OPTION value="{$el.id}"{if $entry[$selected_language].periods_id==$el.id} selected{/if}>{$el.name}</OPTION>
    {/foreach}
   </SELECT>  </TD>
 </TR>
 <tr><td colspan="2">&nbsp;</td></tr>

 <TR>
  <TD valign="top" colspan="2">

   <TABLE style="border-collapse:collapse;" cellpadding="2">
    <TR>
     <TD colspan="2" class="wb"><strong><div>{#dopInfoEvent#}:</div></strong></TD>
    </TR>
    <TR>
     <TD width="350">{#participantsAll#}:</TD>
     <TD><INPUT type="text" name="common[partic_num]" size="10" value="{$entry[$selected_language].partic_num}"></TD>
    </TR>
    <TR>
     <TD>{#localParticipants#}:</TD>
     <TD><INPUT type="text" name="common[local_partic_num]" size="10" value="{$entry[$selected_language].local_partic_num}"></TD>
    </TR>
    <TR>
     <TD>{#foreingParticipants#}:</TD>
     <TD><INPUT type="text" name="common[foreign_partic_num]" size="10" value="{$entry[$selected_language].foreign_partic_num}"></TD>
    </TR>
    <TR>
     <TD>{#totalArea#}:</TD>
     <TD><INPUT type="text" name="common[s_event_total]" size="10" value="{$entry[$selected_language].s_event_total}"></TD>
    </TR>
    <TR>
     <TD>{#visitorsAll#}:</TD>
     <TD><INPUT type="text" name="common[visitors_num]" size="10" value="{$entry[$selected_language].visitors_num}"></TD>
    </TR>
{*    <TR>
     <TD>{#localVisitors#}:</TD>
     <TD><INPUT type="text" name="common[local_visitors_num]" size="10" value="{$entry[$selected_language].local_visitors_num}"></TD>
    </TR>
    <TR>
     <TD>{#foreingVisitors#}:</TD>
     <TD><INPUT type="text" name="common[foreign_visitors_num]" size="10" value="{$entry[$selected_language].foreign_visitors_num}"></TD>
    </TR> *}
   </TABLE>
  </TD>
</TR>
{foreach from=$list_languages item="lang"}
<TR>
  <TD valign="top" colspan="2">
  <TABLE style="border-collapse:collapse;">
    <TR><Td class="wb" colspan="2"><strong>{#contactsProjectGroup#} ({$lang.name})</strong></Td></TR>
    <TR>
    <TD width="350">Email: </TD>
    <TD><INPUT type="text" name="{$lang.code}[email]" size="25" id="email_{$lang.code}" value="{$entry[$lang.code].email}"></TD>
    </TR>
    <TR>
    <TD>{#webPage#}: </TD>
    <TD><INPUT type="text" name="{$lang.code}[web_address]" size="25" id="web_address_{$lang.code}" value="{$entry[$lang.code].web_address}"></TD>
    </TR>
    <TR>
    <TD>{#phone#}: </TD>
    <TD><INPUT type="text" name="{$lang.code}[phone]" size="25" id="phone_{$lang.code}" value="{$entry[$lang.code].phone}"></TD>
    </TR>
    <TR>
    <TD>{#fax#}: </TD>
    <TD><INPUT type="text" name="{$lang.code}[fax]" size="25" id="fax_{$lang.code}" value="{$entry[$lang.code].fax}"></TD>
    </TR>
    <TR>
    <TD>{#contactPerson#}: </TD>
    <TD><INPUT type="text" name="{$lang.code}[cont_pers_name]" size="25" id="cont_pers_name_{$lang.code}" value="{$entry[$lang.code].cont_pers_name}"></TD>
    </TR>
{*    <TR>
    <TD>{#contactPersonPhone#}: </TD>
    <TD><INPUT type="text" name="{$lang.code}[cont_pers_phone]" size="25" id="cont_pers_phone_{$lang.code}" value="{$entry[$lang.code].cont_pers_phone}"></TD>
    </TR>
    <TR>
    <TD>{#contactPersonMail#}: </TD>
    <TD><INPUT type="text" name="{$lang.code}[cont_pers_email]" size="25" id="cont_pers_email_{$lang.code}" value="{$entry[$lang.code].cont_pers_email}"></TD>
    </TR> *}
  </TABLE>
  </TD>
 </TR>
{/foreach}

 <tr><td colspan="2">&nbsp;</td></tr>
 {* foreach from=$list_languages item="lang"}
 <TR>
  <TD class="wb" valign="top" width="200">{#eventNumber#} ({$lang.name}):</TD>
  <TD>
	 <INPUT type="text" size="5" name="{$lang.code}[number]" id="number_{$lang}" value="{$entry[$selected_language].cont_pers_email}" /><BR />  </TD>
 </TR>
 {/foreach *}

 {foreach from=$list_languages item="lang"}
 <TR>
  <TD class="wb" valign="top" width="200">{#workingHours#} ({$lang.name}):</TD>
  <TD>
	 <TEXTAREA name="{$lang.code}[work_time]" style="width:400px; height:50px;" id="work_time_{$lang.code}">{$entry[$lang.code].work_time}</TEXTAREA><BR />  </TD>
 </TR>
 {/foreach}

 {foreach from=$list_languages item="lang"}
 <TR>
  <TD valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="wb">{#exhibitionSectors#} ({$lang.name}): </td>
		</tr>
	</table>
  	<BR /><BR />  </TD>
 <TD><textarea name="{$lang.code}[thematic_sections]" id="thematic_sections_{$lang.code}" style="width:99%; height:250;">{$entry[$lang.code].thematic_sections|escape:"html"}</textarea></TD>
 </TR>
 {/foreach}

 {foreach from=$list_languages item="lang"}
 <TR>
  <TD valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="wb">{#desc#} ({$lang.name}):</td>
		</tr>
	</table>
  	<BR />  </TD>
 <TD><textarea name="{$lang.code}[description]" id="description_{$lang.code}" style="width:99%; height:250px;">{$entry[$lang.code].description|escape:"html"}</textarea></TD>
 </TR>
 {/foreach}

</TABLE>

<BR />
<CENTER>
 {#comments_base_editor#}:<BR />
 <TEXTAREA name="common[comments]" style="width:90%; height:100px;">{$entry[$selected_language].comments}</TEXTAREA><BR />

<TABLE width="60%" align="center">
 <TR>
  <TD align="center"><INPUT type="button" value="{#clear#}" onclick="document.location.href=document.location.href;" /></TD>
  <TD align="center"><INPUT type="button" value="{#preview#}" onclick="preview();" /></TD>
  <TD align="center"><INPUT type="submit" value="{#send_verification#}" /></TD>
 </TR>
</TABLE>

</CENTER>

</FORM>