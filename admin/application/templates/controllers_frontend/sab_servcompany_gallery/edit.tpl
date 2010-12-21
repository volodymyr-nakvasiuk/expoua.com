{strip}{assign var="ve_textareas" value=""}
{foreach from=$list_languages item="lang" name="lang_fe"}
{assign var="ve_textareas" value="`$ve_textareas`thematic_sections_`$lang.code`,description_`$lang.code`"}
{if !$smarty.foreach.lang_fe.last}{assign var="ve_textareas" value="`$ve_textareas`,"}{/if}
{/foreach}{/strip}

{include file="common/orgContentVisualEdit.tpl" textarea=$ve_textareas}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT type="text/javascript" language="javascript">

objCitiesList = Shelby_Backend.ListHelper.cloneObject('objCitiesList');

objCitiesList.columns = new Array(new Array('name', '{#city#}'), new Array('country_name', '{#country#}'));
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
    var tmp = '<SELECT name="common[expocenters_id]" id="expocenters_id"><option value="">({/literal}{#notSelect#}{literal})</option>';
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
  changeLanguage('{/literal}{$selected_language}{literal}');
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

function changeLanguage(code) {
  {/literal}{foreach from=$list_languages item="lang"}
  $("#data_top_{$lang.code}").css("display", "none");
  $("#data_bottom_{$lang.code}").css("display", "none");
  {/foreach}{literal}
  $("#data_top_" + code).css("display", "block");
  $("#data_bottom_" + code).css("display", "block");

  $("#langbar td").removeClass('active');
  $("#langbar #langtab-" + code).addClass('active');
}

{/literal}
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="update"}" enctype="multipart/form-data" id="mainform">

<INPUT type="hidden" name="common[brands_id]" id="brands_id" value="{$entry[$selected_language].brands_id}" />
<INPUT type="hidden" name="brand_categories_name" id="brand_categories_name" value="" />
<INPUT type="hidden" name="city_name" id="city_name" value="" />
<INPUT type="hidden" name="expocenter_name" id="expocenter_name" value="" />
<INPUT type="hidden" name="period_name" id="period_name" value="" />

<DIV align="right">
<a href="{getUrl controller="sab_organizer_eventsfiles" action="list" event_id=$entry[$selected_language].id}">{#attache#}</A>
</DIV>

<p class="important">{#msgLanguageDisclaimer#}</p>

{include file="common/editor-langbar.tpl"}

{foreach from=$list_languages item="lang"}
<TABLE border="0" width="100%" style="border-collapse:collapse; display:none;" cellpadding="5" id="data_top_{$lang.code}">
  <TR>
  <TD class="wb">{#logo#}:</TD>
  <TD>
    {if $entry[$lang.code].logo==1}<img src="/data/images/events/logo/{$entry[$lang.code].languages_id}/{$entry[$lang.code].id}.jpg" align="absmiddle" />{else}({#notUpload#}){/if}
    <a href="{getUrl controller="sab_organizer_eventslogo" action="edit" id=$entry[$lang.code].id}">{#add_edit#}</A>  </TD>
</TR>
<TR>
  <TD class="wb">{#exhName#}:</TD>
  <TD><input type="text" size="60" name="{$lang.code}[brand_name_new]" id="brand_name_new_{$lang.code}" value="{$entry[$lang.code].brand_name}" /></TD>
</TR>
<TR>
  <TD class="wb" valign="top">{#axhfullName#}:</TD>
  <TD width="75%"><textarea name="{$lang.code}[brand_name_extended_new]" id="brand_name_extended_new_{$lang.code}" style="width:90%; height:40px;">{$entry[$lang.code].brand_name_extended}</textarea></TD>
</TR>
</TABLE>
{/foreach}

<TABLE border="0" width="100%" style="border-collapse:collapse;" cellpadding="5">
<TR>
  <TD class="wb">{#exhMainTheme#}:</TD>
  <TD>
  <SELECT name="common[brand_categories_id]" id="brand_categories_id">
  {foreach from=$list_brand_categories item="el"}
    <OPTION value="{$el.id}"{if $entry[$selected_language].brands_categories_id==$el.id} selected{/if}>{$el.name}</OPTION>
  {/foreach}
  </SELECT>  </TD>
</TR>
<TR>
  <TD class="wb">{#city#}:</TD>
  <TD><INPUT type="hidden" name="common[cities_id]" id="cities_id" value="{$entry[$selected_language].cities_id}"> <INPUT type="button" onclick="objCitiesList.showPopUp();" value=" {#chooseAction#} "> <SPAN id="cities_id_name">{$entry[$selected_language].city_name}</SPAN></TD>
</TR>
<TR>
  <TD class="wb">{#exhCenter#}:</TD>
  <TD id="expocenters_id">({#selectCityFirst#})</TD>
</TR>
<TR>
  <TD class="wb">{#datesExh#}:</TD>
  <TD>{#captionFrom#} <INPUT type="text" size="12" name="common[date_from]" id="date_from" onchange="$('#date_to').val(this.value);" value="{$entry[$selected_language].date_from}"> {#captionTo#} <INPUT type="text" size="12" name="common[date_to]" id="date_to" value="{$entry[$selected_language].date_to}"></TD>
</TR>
<TR>
  <TD class="wb">{#periodEvent#}:</TD>
  <TD>
  <SELECT name="common[periods_id]" id="periods_id">
    <OPTION value="">({#notSelect#})</OPTION>
    {foreach from=$list_periods item="el"}
    <OPTION value="{$el.id}"{if $el.id==$entry[$selected_language].periods_id} selected{/if}>{$el.name}</OPTION>
    {/foreach}
  </SELECT>  </TD>
</TR>
<TR>
  <TD colspan="2">&nbsp;</TD>
  </TR>

<TR>
  <TD colspan="2" valign="top">

  <TABLE style="border-collapse:collapse; margin-right:50px;" align="left">
    <TR><Td class="wb" colspan="2"><strong>{#dopInfoEvent#}</strong></Td></TR>
    <TR>
    <TD>{#participantsAll#}:</TD>
    <TD><INPUT type="text" name="common[partic_num]" size="10" id="partic_num" value="{$entry[$selected_language].partic_num}"></TD>
    </TR>
    <TR>
    <TD>{#localParticipants#}:</TD>
    <TD><INPUT type="text" name="common[local_partic_num]" size="10" id="local_partic_num" value="{$entry[$selected_language].local_partic_num}"></TD>
    </TR>
    <TR>
    <TD>{#foreingParticipants#}:</TD>
    <TD><INPUT type="text" name="common[foreign_partic_num]" size="10" id="foreign_partic_num" value="{$entry[$selected_language].foreign_partic_num}"></TD>
    </TR>
    <TR>
    <TD>{#totalArea#}:</TD>
    <TD><INPUT type="text" name="common[s_event_total]" size="10" id="s_event_total" value="{$entry[$selected_language].s_event_total}"></TD>
    </TR>
    <TR>
    <TD>{#visitorsAll#}:</TD>
    <TD><INPUT type="text" name="common[visitors_num]" size="10" id="visitors_num" value="{$entry[$selected_language].visitors_num}"></TD>
    </TR>
    <TR>
    <TD>{#localVisitors#}:</TD>
    <TD><INPUT type="text" name="common[local_visitors_num]" size="10" id="local_visitors_num" value="{$entry[$selected_language].local_visitors_num}"></TD>
    </TR>
    <TR>
    <TD>{#foreingVisitors#}:</TD>
    <TD><INPUT type="text" name="common[foreign_visitors_num]" size="10" id="foreign_visitors_num" value="{$entry[$selected_language].foreign_visitors_num}"></TD>
    </TR>
  </TABLE>

  <TABLE style="border-collapse:collapse;">
    <TR><Td class="wb" colspan="2"><strong>{#contactsProjectGroup#}</strong></Td></TR>
    <TR>
    <TD>Email: </TD>
    <TD><INPUT type="text" name="common[email]" size="25" id="email" value="{$entry[$selected_language].email}"></TD>
    </TR>
    <TR>
    <TD>{#webPage#}: </TD>
    <TD><INPUT type="text" name="common[web_address]" size="25" id="web_address" value="{$entry[$selected_language].web_address}"></TD>
    </TR>
    <TR>
    <TD>{#phone#}: </TD>
    <TD><INPUT type="text" name="common[phone]" size="25" id="phone" value="{$entry[$selected_language].phone}"></TD>
    </TR>
    <TR>
    <TD>{#fax#}: </TD>
    <TD><INPUT type="text" name="common[fax]" size="25" id="fax" value="{$entry[$selected_language].fax}"></TD>
    </TR>
    <TR>
    <TD>{#contactPerson#}: </TD>
    <TD><INPUT type="text" name="common[cont_pers_name]" size="25" id="cont_pers_name" value="{$entry[$selected_language].cont_pers_name}"></TD>
    </TR>
    <TR>
    <TD>{#contactPersonPhone#}: </TD>
    <TD><INPUT type="text" name="common[cont_pers_phone]" size="25" id="cont_pers_phone" value="{$entry[$selected_language].cont_pers_phone}"></TD>
    </TR>
    <TR>
    <TD>{#contactPersonMail#}: </TD>
    <TD><INPUT type="text" name="common[cont_pers_email]" size="25" id="cont_pers_email" value="{$entry[$selected_language].cont_pers_email}"></TD>
    </TR>
  </TABLE>  </TD>
</TR>
</TABLE>

{foreach from=$list_languages item="lang"}
<TABLE border="0" width="100%" style="border-collapse:collapse; display:none;" cellpadding="5" id="data_bottom_{$lang.code}">
<TR>
  <TD colspan="2" valign="top">&nbsp;</TD>
  </TR>

<TR>
  <TD class="wb" valign="top" width="200">{#eventNumber#}:</TD>
  <TD>
  <INPUT type="text" size="5" name="{$lang.code}[number]" id="number_{$lang}" value="{$entry[$selected_language].cont_pers_email}" /><BR />  </TD>
</TR>
<TR>
  <TD class="wb" valign="top" width="200">{#workingHours#}:</TD>
  <TD width="75%">
  <TEXTAREA name="{$lang.code}[work_time]" style="width:99%; height:50px;" id="work_time_{$lang.code}">{$entry[$lang.code].work_time}</TEXTAREA><BR />  </TD>
</TR>
<TR>
  <TD valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td class="wb">{#exhibitionSectors#}: </td>
    </tr>
  </table>
    <BR /><BR />  </TD>
<TD width="75%"><textarea name="{$lang.code}[thematic_sections]" id="thematic_sections_{$lang.code}" style="width:99%; height:250;">{$entry[$lang.code].thematic_sections|escape:"html"}</textarea></TD>
</TR>

<TR>
  <TD valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td class="wb">{#desc#}:</td>
    </tr>
  </table>
    <BR />  </TD>
<TD width="75%"><textarea name="{$lang.code}[description]" id="description_{$lang.code}" style="width:99%; height:250px;">{$entry[$lang.code].description|escape:"html"}</textarea></TD>
</TR>
</TABLE>
{/foreach}

<p class="important">{#msgLanguageDisclaimer#}</p>

<center>
<INPUT type="hidden" name="common[status]" value="1" /><BR />
{#comments_base_editor#}:<BR />
<TEXTAREA name="common[comments]" style="width:90%; height:100px;"></TEXTAREA><BR />
</center>

<TABLE width="60%" align="center">
<TR>
  <TD align="center"><INPUT type="button" value="{#clear#}" onclick="document.location.href=document.location.href;" /></TD>
  <TD align="center"><INPUT type="button" value="{#preview#}" onclick="preview();" /></TD>
  <TD align="center"><INPUT type="submit" value="{#send_verification#}" /></TD>
</TR>
</TABLE>

</FORM>