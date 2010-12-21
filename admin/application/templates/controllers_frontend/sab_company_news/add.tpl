{strip}{assign var="ve_textareas" value=""}
{foreach from=$list_user_languages item="lang" name="lang_fe"}
{assign var="ve_textareas" value="`$ve_textareas`content_`$lang.code`"}
{if !$smarty.foreach.lang_fe.last}{assign var="ve_textareas" value="`$ve_textareas`,"}{/if}
{/foreach}{/strip}
{include file="common/orgContentVisualEdit.tpl" textarea=$ve_textareas}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>
<SCRIPT type="text/javascript" src="{$document_root}js/adminFormValidator.js"></SCRIPT>

<SCRIPT type="text/javascript">

objValidator = Shelby_Backend.FormValidator.cloneObject('objValidator');

objValidator.headerMessage = "{#msgThereAreErrors#}:\n\n";
objValidator.addField('name_{$user_language.code}', 'text', 3, "{#msgEnterNewsHeader#}\n");

objCountriesList = Shelby_Backend.ListHelper.cloneObject('objCountriesList');
objEventsList = Shelby_Backend.ListHelper.cloneObject('objEventsList');

objCountriesList.columns = new Array(new Array('name', '{#captionCountry#}'));
objCountriesList.returnFieldId = 'countries_id';
objCountriesList.feedUrl = '{getUrl controller="sab_jsonfeeds" action="countries" sort="name:ASC"}';
objCountriesList.writeForm();

objEventsList.columns = new Array(new Array('date_from', '{#captionDate#}'), new Array('brand_name', '{#captionName#}'));
objEventsList.returnFieldId = 'events_id';
objEventsList.feedUrl = '{getUrl controller="sab_jsonfeeds" action="events"}';
objEventsList.writeForm();

{literal}
objEventsList.callbackUser = function(entry) {
	$("#events_id_name").html(entry.brand_name + " (" + entry.date_from + ")");
}

$(document).ready(function() {
	$('#date_public').datepicker();
});
{/literal}
</SCRIPT>

<h4>{#hdrAddingNewCompanyNews#}</h4>

<input type="button" value="{#linkBackToList#}" onClick="document.location.href='{getUrl add="1" action="list" del="id"}';"><br><br>

<FORM method="post" action="{getUrl add="1" action="insert"}" onsubmit="return objValidator.validate();" enctype="multipart/form-data">

<TABLE border="0" width="100%" style="border-collapse:collapse;">
 <TR>
{foreach from=$list_user_languages item="lang"}
 <tr>
  <td colspan="2"><h5>{$lang.name}</h5></td>
 </tr>

 <TR>
  <TD class="wb">{#captionName#}{if $lang.id==$company_data.language_id}*{/if}:</TD>
  <TD><INPUT type="text" size="70" name="{$lang.code}[name]" id="name_{$lang.code}"></TD>
 </TR>
{if $lang.id==$company_data.language_id}
 <TR>
  <TD class="wb">{#captionDate#}:</TD>
  <TD><INPUT type="text" size="12" name="common[date_public]" id="date_public" value="{$smarty.now|date_format:"%Y-%m-%d"}"></TD>
 </TR>
 <TR>
  <TD class="wb">{#captionImage#}:</TD>
  <TD><INPUT type="file" name="logo"></TD>
 </TR>
{/if}
 <TR>
  <TD class="wb">{#captionDescription#}:</TD>
  <TD>
   <TEXTAREA name="{$lang.code}[content]" id="content_{$lang.code}" style="width:95%; height:500px;"></TEXTAREA>
  </TD>
 </TR>
 
 <tr>
  <td colspan="2">&nbsp;</td>
 </tr>
{/foreach}

 <TR><TD align="center" colspan="2"><BR />
  <INPUT type="submit" value="{#addAction#}">
 </TD></TR>
</TABLE>

</FORM>