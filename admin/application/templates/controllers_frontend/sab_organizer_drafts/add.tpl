{strip}{assign var="ve_textareas" value=""}
{foreach from=$list_languages item="lang" name="lang_fe"}
{assign var="ve_textareas" value="`$ve_textareas`thematic_sections_`$lang.code`,description_`$lang.code`"}
{if !$smarty.foreach.lang_fe.last}{assign var="ve_textareas" value="`$ve_textareas`,"}{/if}
{/foreach}{/strip}
{include file="common/orgContentVisualEdit.tpl" textarea=$ve_textareas}

<script type="text/javascript" src="{$document_root}js/adminFormValidator.js"></script>
<script type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></script>

<script type="text/javascript" language="javascript">
objValidator = Shelby_Backend.FormValidator.cloneObject('objValidator');
objValidator.headerMessage = "{#msgThereAreErrors#}:\n\n";

objValidator.addField('cities_id', 'num', 1, "{#msgChooseCity#}\n");

objBrandsList = Shelby_Backend.ListHelper.cloneObject('objBrandsList');
objCitiesList = Shelby_Backend.ListHelper.cloneObject('objCitiesList');
objExpocentersList = Shelby_Backend.ListHelper.cloneObject('objExpocentersList');

objBrandsList.columns = new Array(new Array('name', '{#city#}'));
objBrandsList.returnFieldId = 'brands_id';
objBrandsList.feedUrl = '{getUrl controller="sab_jsonfeeds" action="brands" results="999" sort="name:ASC"}';
objBrandsList.persistentFilter = 'organizers_id={$session_user_orgid}';
objBrandsList.writeForm();

objCitiesList.columns = new Array(new Array('name', '{#city#}'), new Array('country_name', '{#country#}'));
objCitiesList.returnFieldId = 'cities_id';
objCitiesList.feedUrl = '{getUrl controller="sab_jsonfeeds" action="cities" sort="name:ASC"}';
objCitiesList.writeForm();

{literal}

objBrandsList.callbackUser = function(entry) {
  var url = '{/literal}{getUrl controller="sab_jsonfeeds" action="lasteventbybrand"}{literal}' + 'id/' + entry.id + "/";

  $.getJSON(url, function(json) {
    //alert(json.entry.ru.id);
    {/literal}
    {foreach from=$list_languages item="lang"}
    fillLangFields('{$lang.code}', json.entry.{$lang.code});
    {/foreach}

    $("#events_id").val(json.entry.ru.id);

    $("#partic_num").val(json.entry.ru.partic_num);
    $("#local_partic_num").val(json.entry.ru.local_partic_num);
    $("#foreign_partic_num").val(json.entry.ru.foreign_partic_num);
    $("#s_event_total").val(json.entry.ru.s_event_total);
    $("#visitors_num").val(json.entry.ru.visitors_num);
    $("#local_visitors_num").val(json.entry.ru.local_visitors_num);
    $("#foreign_visitors_num").val(json.entry.ru.foreign_visitors_num);

    $("#cities_id").val(json.entry.ru.cities_id);
    $("#cities_id_name").html(json.entry.{$selected_language}.city_name);
    createExpoCentersList(json.entry.ru.cities_id, json.entry.ru.expocenters_id);

    $("select[@id='periods_id'] option[@value='" + json.entry.ru.periods_id + "']").attr("selected", "selected");
    $("select[@id='brand_categories_id'] option[@value='" + entry.brands_categories_id + "']").attr("selected", "selected");
    {literal}
  });
}

function fillLangFields(lang, data) {
  $("#brand_name_new_" + lang).val(data.brand_name);
  $("#brand_name_extended_new_" + lang).val(data.brand_name_extended);

  $('#work_time_' + lang).val(data.work_time);
  $('#thematic_sections_' + lang).val(data.thematic_sections);
  $('#description_' + lang).val(data.description);

  $("#email_" + lang).val(data.email);
  $("#web_address_" + lang).val(data.web_address);
  $("#phone_" + lang).val(data.phone);
  $("#fax_" + lang).val(data.fax);
  $("#cont_pers_name_" + lang).val(data.cont_pers_name);
  $("#cont_pers_phone_" + lang).val(data.cont_pers_phone);
  $("#cont_pers_email_" + lang).val(data.cont_pers_email);

  tinyMCE.updateContent('thematic_sections_' + lang);
  tinyMCE.updateContent('description_' + lang);
}

objCitiesList.callbackUser = function(entry) {
  createExpoCentersList(entry.id, 0);
}

function createExpoCentersList(id, selected) {
  var url = '{/literal}{getUrl controller="sab_jsonfeeds" action="expocenters" results="1000" sort="name:ASC"}{literal}search/cities_id:' + id + '/';

  $.getJSON(url, function(json) {
    var tmp = '<SELECT name="common[expocenters_id]" id="expocenters_id"><option value="">({/literal}{#notSelect#}{literal})</option>';
    $.each(json.list.data, function(i) {
      tmp += '<option value="' + json.list.data[i].id + '"';
      if (json.list.data[i].id==selected) {
        tmp += ' selected="selected"';
      }
      tmp += '>' + json.list.data[i].name + '</option>';
    });
    tmp += "</SELECT>";
    $("#expocenters_id").html(tmp);
  });
}

function createSubcategoriesList(id) {
  $("#brand_subcategories_id").empty();
  $("#brand_subcategories_id").attr("disabled", "disabled");
  if (id > 0) {
    var url = "{/literal}{getUrl controller="sab_jsonfeeds" action="brandssubcategories"}{literal}parent/" + id + "/";
    $.getJSON(url, function(json) {
      $.each(json.list.data, function(i) {
        $("#brand_subcategories_id").append("<option value='" + json.list.data[i].id + "'>" + json.list.data[i].name + "</option>");
      });
      $("#brand_subcategories_id").removeAttr("disabled");
    });
  }
}

$(document).ready(function(){
  $('#date_from').datepicker();
  $('#date_to').datepicker();
  changeLanguage('{/literal}{$selected_language}{literal}');
  createSubcategoriesList($("#brand_categories_id option:selected").val());
});

function preview() {
  var insertAction = '{/literal}{getUrl add="1" action="insert"}{literal}';
  var previewAction = '{/literal}{getUrl add="1" action="preview"}{literal}';

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
  $("#data_contacts_{$lang.code}").css("display", "none");
  {/foreach}{literal}
  $("#data_top_" + code).css("display", "block");
  $("#data_bottom_" + code).css("display", "block");
  $("#data_contacts_" + code).css("display", "block");
}

{/literal}
</SCRIPT>

<!-- <H4>{#add_new_exh#}</H4> -->

<p style="border:1px solid #900; background:#FDEBEA; padding:10px;">{#disclaimer#}</p>

<FORM method="post" action="{getUrl add="1" action="insert"}" target="_self" id="mainform" enctype="multipart/form-data" onsubmit="return objValidator.validate();">
<input type="hidden" name="common[brand_organizers_id]" value="{$session_user_orgid}" />
<INPUT type="hidden" name="common[events_id]" id="events_id" value="" />

<INPUT type="hidden" name="brand_categories_name" id="brand_categories_name" value="" />
<INPUT type="hidden" name="city_name" id="city_name" value="" />
<INPUT type="hidden" name="expocenter_name" id="expocenter_name" value="" />
<INPUT type="hidden" name="period_name" id="period_name" value="" />

{#use_pastexh#}

<TABLE border="0" width="100%" style="border-collapse:collapse;" cellpadding="5">
<TR>
  <TD width="200" class="wb">{#find_pastexh#}: </TD>
  <TD><INPUT type="hidden" name="common[brands_id]" id="brands_id"> <INPUT type="button" onclick="objBrandsList.showPopUp();" value=" {#chooseAction#} "> <SPAN id="brands_id_name"></SPAN>
  <INPUT type="button" value=" {#clearAction#} " onclick="document.location.href=document.location.href;" />  </TD>
</TR>
{* if $selected_language == 'ru'}
<TR class="important">
  <TD width="200" class="wb">{#captionPostingConditions#}: </TD>
  <TD><INPUT type="radio" name="common[premium]" value="0" checked="checked" /> {#captionForFree#} &nbsp; &nbsp; <INPUT type="radio" name="common[premium]" value="1" /> {#captionPremium#} <a href="{getUrl controller="sab_organizer_premium" action="add"}" onclick="NewWindow(this.href,'premium','600','450','yes','center');return false" onfocus="this.blur()">({#captionMore#})</a></TD>
</TR>
{/if *}
<TR>
  <TD colspan="2">{#notfind_pastexh#}</TD>
</TR>
</table>

<p class="important">{#msgLanguageDisclaimer#}</p>

<div class="box box-noborders tabs-holder relative">
	<ul class="tabs">
		{foreach from=$list_languages item="lang"}
		<li class="item{if $lang.code==$selected_language} current{/if}"><a href="javascript:changeLanguage('{$lang.code}');" class="tab"><ins class="t">{$lang.name}</ins></a></li>
		{/foreach}
	</ul>
	<div class="contents" style="height:0; border-width:1px 0 0;"><!-- --></div>
	<script type="text/javascript">
	<!--{literal}
	$.fn.minitabs = function() {
		return this.each(function(){
			var $this = $(this);
			var $tabs = $this.find('.tabs').children();
			var $contents = $this.find('.contents').children();
			var selected = 0;
			$tabs.each(function(index){
				var $tab = $(this);
				if ($tab.hasClass('current'))
					selected = index;
				$tab.find('.tab').click(function(e){
					$tabs.eq(selected).removeClass('current');
					$contents.eq(selected).hide();
					selected = index;
					$tabs.eq(selected).addClass('current');
					$contents.eq(selected).show();
					
				});			
			});
		});
	}
	$(document).ready(function(){ $('.tabs-holder').minitabs(); });
	{/literal}//-->
	</script>
</div>

{foreach from=$list_languages item="lang"}
<TABLE border="0" width="100%" style="border-collapse:collapse;" cellpadding="5" id="data_top_{$lang.code}">
<TR>
  <TD width="200" class="wb">{#exhName#}:</TD>
  <TD><input type="text" size="60" name="{$lang.code}[brand_name_new]" id="brand_name_new_{$lang.code}" /></TD>
</TR>
<TR>
  <TD width="200" valign="top" class="wb">{#axhfullName#}:</TD>
  <TD><textarea name="{$lang.code}[brand_name_extended_new]" id="brand_name_extended_new_{$lang.code}" style="width:90%; height:40px;"></textarea></TD>
</TR>
<TR>
 <td class="wb">{#logo#}:</td>
 <td><input type="file" name="logo_{$lang.code}"/></td>
</TR>
</TABLE>
{/foreach}

<TABLE border="0" width="100%" style="border-collapse:collapse;" cellpadding="5">
<TR>
  <TD width="200" class="wb">{#exhMainTheme#}:</TD>
  <TD>
  <SELECT name="common[brand_categories_id]" id="brand_categories_id" onchange="createSubcategoriesList(this.value);">
  {foreach from=$list_brand_categories item="el"}
    <OPTION value="{$el.id}">{$el.name}</OPTION>
  {/foreach}
  </SELECT>
  </TD>
</TR>
<TR>
  <TD width="200" class="wb">{#exhSubCategory#}:</TD>
  <TD>
  <SELECT name="common[brand_subcategories_id][]" id="brand_subcategories_id" multiple="multiple" disabled="disabled" style="width:500px; height:80px;"></SELECT>
  </TD>
</TR>
<TR>
  <TD width="200" class="wb">{#city#}:</TD>
  <TD><INPUT type="hidden" name="common[cities_id]" id="cities_id" onchange="createExpoCentersList(this.value);"> <INPUT type="button" onclick="objCitiesList.showPopUp();" value=" {#chooseAction#} "> <SPAN id="cities_id_name"></SPAN></TD>
</TR>
<TR>
  <TD width="200" class="wb">{#exhCenter#}:</TD>
  <TD id="expocenters_id">({#selectCityFirst#})</TD>
</TR>
<TR>
  <TD width="200" class="wb">{#datesExh#}:</TD>
  <TD>{#captionFrom#} <INPUT type="text" size="12" name="common[date_from]" id="date_from" onchange="$('#date_to').val(this.value);"> {#captionTo#} <INPUT type="text" size="12" name="common[date_to]" id="date_to"></TD>
</TR>
<TR>
  <TD width="200" class="wb">{#periodEvent#}:</TD>
  <TD>
  <SELECT name="common[periods_id]" id="periods_id">
    <OPTION value="">({#notSelect#})</OPTION>
    {foreach from=$list_periods item="el"}
    <OPTION value="{$el.id}">{$el.name}</OPTION>
    {/foreach}
  </SELECT>
  </TD>
</TR>
<tr>
	  <td>{#exhAdmission#}:</td>
	  <td>
	   <SELECT name="common[is_free]">
	    <OPTION value="">({#notSelect#})</OPTION>
	    <OPTION value="1">{#captionForFree#}</OPTION>
	    <OPTION value="0">{#captionPaid#}</OPTION>
	   </SELECT>
	  </td>
	 </tr>
	 <tr>
	  <td>{#ticketFee#}:</td>
	  <td><input type="text" name="common[ticket_fee]" value=""/></td>
</tr>
<TR>
  <TD colspan="2">&nbsp;</TD>
</TR>

<TR>
  <TD colspan="2" valign="top">

  <TABLE style="border-collapse:collapse; margin-right:50px;" align="left">
    <TR><td class="wb" align="center" colspan="2"><div align="left"><strong>{#dopInfoEvent#}</strong></div></td></TR>
    <TR>
    <TD width="350">{#participantsAll#}:</TD>
    <TD><INPUT name="common[partic_num]" type="text" id="partic_num" value="0" size="10"></TD>
    </TR>
    <TR>
    <TD>{#localParticipants#}:</TD>
    <TD><INPUT name="common[local_partic_num]" type="text" id="local_partic_num" value="0" size="10"></TD>
    </TR>
    <TR>
    <TD>{#foreingParticipants#}:</TD>
    <TD><INPUT name="common[foreign_partic_num]" type="text" id="foreign_partic_num" value="0" size="10"></TD>
    </TR>
    <TR>
    <TD>{#totalArea#}:</TD>
    <TD><INPUT name="common[s_event_total]" type="text" id="s_event_total" value="0" size="10"></TD>
    </TR>
    <TR>
    <TD>{#visitorsAll#}:</TD>
    <TD><INPUT name="common[visitors_num]" type="text" id="visitors_num" value="0" size="10"></TD>
    </TR>
{*    <TR>
    <TD>{#localVisitors#}:</TD>
    <TD><INPUT name="common[local_visitors_num]" type="text" id="local_visitors_num" value="0" size="10"></TD>
    </TR>
    <TR>
    <TD>{#foreingVisitors#}:</TD>
    <TD><INPUT name="common[foreign_visitors_num]" type="text" id="foreign_visitors_num" value="0" size="10"></TD>
    </TR> *}
  </TABLE>
</TD></TR>
<TR><TD colspan="2" valign="top">
{foreach from=$list_languages item="lang"}
  <TABLE style="border-collapse:collapse; display:none;" id="data_contacts_{$lang.code}">
    <TR><td class="wb" align="center" colspan="2"><div align="left"><strong>{#contactsProjectGroup#}</strong></div></td></TR>
    <TR>
    <TD width="350">Email: </TD>
    <TD><INPUT type="text" name="{$lang.code}[email]" size="25" id="email_{$lang.code}"></TD>
    </TR>
    <TR>
    <TD>{#webPage#}: </TD>
    <TD><INPUT type="text" name="{$lang.code}[web_address]" size="25" id="web_address_{$lang.code}"></TD>
    </TR>
    <TR>
    <TD>{#phone#}: </TD>
    <TD><INPUT type="text" name="{$lang.code}[phone]" size="25" id="phone_{$lang.code}"></TD>
    </TR>
    <TR>
    <TD>{#fax#}: </TD>
    <TD><INPUT type="text" name="{$lang.code}[fax]" size="25" id="fax_{$lang.code}"></TD>
    </TR>
    <TR>
    <TD>{#contactPerson#}: </TD>
    <TD><INPUT type="text" name="{$lang.code}[cont_pers_name]" size="25" id="cont_pers_name_{$lang.code}"></TD>
    </TR>
{*    <TR>
    <TD>{#contactPersonPhone#}: </TD>
    <TD><INPUT type="text" name="{$lang.code}[cont_pers_phone]" size="25" id="cont_pers_phone_{$lang.code}"></TD>
    </TR>
    <TR>
    <TD>{#contactPersonMail#}: </TD>
    <TD><INPUT type="text" name="{$lang.code}[cont_pers_email]" size="25" id="cont_pers_email_{$lang.code}"></TD>
    </TR> *}
  </TABLE>
{/foreach}
 </TD>
</TR>
<TR>
  <TD colspan="2" valign="top">&nbsp;</TD>
  </TR>
</TABLE>

{include file="controllers_frontend/sab_organizer_drafts/add_lang_spec.tpl"}

<p class="important">{#msgLanguageDisclaimer#}</p>

<BR />
<CENTER>
<INPUT type="hidden" name="common[status]" value="1" /><BR />
{#comments_base_editor#}:<BR />
<TEXTAREA name="common[comments]" style="width:90%; height:100px;"></TEXTAREA><BR />
</CENTER>

<TABLE width="60%" align="center">
<TR>
  <TD align="center"><INPUT type="button" value="{#clear#}" onclick="document.location.href=document.location.href;" /></TD>
  <TD align="center"><INPUT type="button" value="{#preview#}" onclick="preview();" /></TD>
  <TD align="center"><INPUT type="submit" value="{#send_verification#}" /></TD>
</TR>
</TABLE>

</FORM>