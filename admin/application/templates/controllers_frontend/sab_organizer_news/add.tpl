{strip}{assign var="ve_textareas" value=""}
{foreach from=$list_languages item="lang" name="lang_fe"}
{assign var="ve_textareas" value="`$ve_textareas`content_`$lang.code`"}
{if !$smarty.foreach.lang_fe.last}{assign var="ve_textareas" value="`$ve_textareas`,"}{/if}
{/foreach}{/strip}
{include file="common/orgContentVisualEdit.tpl" textarea=$ve_textareas}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>

objBrandsList = Shelby_Backend.ListHelper.cloneObject('objBrandsList');

objBrandsList.columns = new Array(new Array('name', '{#title#}'));
objBrandsList.returnFieldId = 'brands_id';
objBrandsList.feedUrl = '{getUrl controller="sab_jsonfeeds" action="brands" results="999" sort="name:ASC"}';
objBrandsList.persistentFilter = 'organizers_id={$session_user_orgid}';
objBrandsList.writeForm();

{literal}

objBrandsList.callbackUser = function(entry) {
	$("#brands_categories_id").val(entry.brands_categories_id);
	$("#brand_name").val(entry.name);
}

function preview() {
	var insertAction = '{/literal}{getUrl add="1" action="insert"}{literal}';
	var previewAction = '{/literal}{getUrl add="1" action="preview"}{literal}';

	$('#mainform').attr('action', previewAction);
	$('#mainform').attr('target', 'news_preview');

	$('#mainform').submit();

	$('#mainform').attr('action', insertAction);
	$('#mainform').attr('target', '_self');
}

$(document).ready(function() {
	$('#date_public').datepicker();
	changeLanguage('{/literal}{$selected_language}{literal}');
});

function changeLanguage(code) {
  {/literal}{foreach from=$list_languages item="lang"}
  $("#data_top_{$lang.code}").css("display", "none");
  {/foreach}{literal}
  $("#data_top_" + code).css("display", "block");

  $("#langbar td").removeClass('active');
  $("#langbar #langtab-" + code).addClass('active');
}
{/literal}
</script>

<form method="post" action="{getUrl add="1" action="insert"}" id="mainform">

<input type="hidden" name="brand_name" id="brand_name" value="" />
<input type="hidden" name="common[brands_categories_id]" id="brands_categories_id" />

{include file="common/editor-langbar.tpl"}

<table border="0" width="100%" style="border-collapse:collapse;" cellpadding="5">
<TR>
  <TD class="wb">{#chose_exh#}:</TD>
  <TD><INPUT type="hidden" name="common[brands_id]" id="brands_id" value=""> <INPUT type="button" onclick="objBrandsList.showPopUp();" value=" {#chooseAction#} "> <SPAN id="brands_id_name"></SPAN></TD>
 </TR>
<TR>
  <TD class="wb">{#country#}:</TD>
  <TD>
   <SELECT name="countries_id">
    <OPTION value="">({#not_chosen#})</OPTION>
    {foreach from=$list_countries.data item="el"}
     <OPTION value="{$el.id}">{$el.name}</OPTION>
    {/foreach}
   </SELECT>
  </TD>
</TR>
<TR>
  <TD class="wb">{#date_pub#}:</TD>
  <TD><INPUT type="text" size="12" name="common[date_public]" id="date_public" value="{$smarty.now|date_format:"%Y-%m-%d"}"></TD>
</TR>
</TABLE>

{foreach from=$list_languages item="lang"}
<TABLE border="0" width="100%" style="border-collapse:collapse;" cellpadding="5" id="data_top_{$lang.code}">
 <TR>
  <TD class="wb">{#title#}:</TD>
  <TD><INPUT type="text" size="50" name="{$lang.code}[name]" value=""></TD>
 </TR>
 <TR>
  <TD valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="wb">{#teazer#}: </td>
		</tr>
	</table>
  	<BR /></TD>
 <TD><textarea name="{$lang.code}[preambula]" id="preambula_{$lang.code}" style="width:95%; height:100px;"></textarea></TD>
 </TR>
 <TR>
  <TD valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="wb">{#full_text#}: </td>
		</tr>
	</table>
  	<BR /></TD>
 <TD><textarea name="{$lang.code}[content]" id="content_{$lang.code}" style="width:95%; height:500px;"></textarea></TD>
 </TR>
</TABLE>
{/foreach}

<TABLE width="60%" align="center">
 <TR>
  <TD align="center"><INPUT type="button" value="{#clear#}" onclick="document.location.href=document.location.href;" /></TD>
  <TD align="center"><INPUT type="button" value="{#preview#}" onclick="preview();" /></TD>
  <TD align="center"><INPUT type="submit" value="{#send_verification#}" /></TD>
 </TR>
</TABLE>

</FORM>