{strip}{assign var="ve_textareas" value=""}
{foreach from=$list_languages item="lang" name="lang_fe"}
{assign var="ve_textareas" value="`$ve_textareas`description_`$lang.code`"}
{if !$smarty.foreach.lang_fe.last}{assign var="ve_textareas" value="`$ve_textareas`,"}{/if}
{/foreach}{/strip}
{include file="common/orgContentVisualEdit.tpl" textarea=$ve_textareas}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>
<SCRIPT type="text/javascript" src="{$document_root}js/adminFormValidator.js"></SCRIPT>

<SCRIPT type="text/javascript">

objValidator = Shelby_Backend.FormValidator.cloneObject('objValidator');

objValidator.headerMessage = "При заполнении формы возникли ошибки:\n\n";
objValidator.addField('name_{$user_language.code}', 'text', 3, "- Введите пожалуйста название компании на языке: {$user_language.name}\n");
objValidator.addField('description_{$user_language.code}', 'tinyMCE', 10, "- Введите пожалуйста описание на языке: {$user_language.name}\n");


{literal}
objValidator.userValidation = function() {
	var n = $("input[name^='companies_to_brands_categories']:checked").length;

	if (n>0) {
		return true;
	} else {
		return "- Выберите хотя бы одну тематику выставок\n";
	}
}
{/literal}

objCitiesList = Shelby_Backend.ListHelper.cloneObject('objCitiesList');

objCitiesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('country_name', 'Страна'));
objCitiesList.returnFieldId = 'cities_id';
objCitiesList.feedUrl = '{getUrl controller="sab_jsonfeeds" action="cities" sort="name:ASC"}';
objCitiesList.writeForm();

{literal}
$(function() {
  $(".cb-cat").click(function() {
    $(".cb-cat").attr('checked', '');
    $(this).attr('checked', 'checked');
  });
});
{/literal}

</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="update" id="0"}" enctype="multipart/form-data" onsubmit="return objValidator.validate();">

<h4>{#hdrEditingCompanyInfo#}</h4>

{if $entry.en.logo==1}<img src="/data/images/companies/{$entry.en.id}/logo.jpg">{/if}

<TABLE border="0" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD class="wb" width="150">{#captionTopics#}*:</TD>
  <TD>
   {foreach from=$list_categories item="el"}
    <label><INPUT type="checkbox" name="companies_to_brands_categories[{$el.id}]" class="cb-cat" value="{$el.id}"{if isset($entry.list_categories[$el.id])} checked{/if}> {$el.name}</label><br />
   {/foreach}
  </TD>
 </TR>

 <TR>
  <TD>{#captionCity#}:</TD>
  <TD><INPUT type="hidden" name="common[cities_id]" id="cities_id" value="{$entry.en.cities_id}"> <INPUT type="button" onclick="objCitiesList.showPopUp();" value="{#chooseAction#}"> <SPAN id="cities_id_name">{$entry[$selected_language].city_name}</SPAN></TD>
 </TR>
 <TR>
  <TD class="wb">{#captionLogo#}:</TD>
  <TD><INPUT type="file" name="logo">{if $entry.en.logo==1} <a href="/data/images/companies/{$entry.en.id}/logo.jpg" target="_blank">Просмотреть</a>{/if}</TD>
 </TR>
 <tr>
  <td colspan="2">&nbsp;</td>
 </tr>

 {foreach from=$list_user_languages item="lang"}
 <tr>
  <td colspan="2"><b>{$lang.name}</b></td>
 </tr>
 <TR>
  <TD class="wb">{#captionName#}{if $lang.id==$company_data.language_id}*{/if}:</TD>
  <TD><INPUT type="text" size="70" name="{$lang.code}[name]" id="name_{$lang.code}" value="{$entry[$lang.code].name|escape:"html"}"></TD>
 </TR>
 <TR>
  <TD class="wb">{#captionDescription#}{if $lang.id==$company_data.language_id}*{/if}:</TD>
  <TD><TEXTAREA name="{$lang.code}[description]" id="description_{$lang.code}" style="width:95%; height:500px;">{$entry[$lang.code].description|escape:"html"}</TEXTAREA></TD>
 </TR>
 <TR>
  <TD class="wb">{#captionAddress#}:</TD>
  <TD><TEXTAREA name="{$lang.code}[address]" style="width:90%; height:50px;">{$entry[$lang.code].address|escape:"html"}</TEXTAREA></TD>
 </TR>

{if $lang.id==$company_data.language_id}
 <TR>
  <TD class="wb">{#captionZipcode#}:</TD>
  <TD><INPUT type="text" name="common[postcode]" size="25" value="{$entry.en.postcode}" /></TD>
 </TR>
 <TR>
  <TD class="wb">{#captionEmail#}:</TD>
  <TD><INPUT type="text" name="common[email]" size="25" value="{$entry.en.email}" /></TD>
 </TR>
 <TR>
  <TD class="wb">{#captionUrl#}:</TD>
  <TD><INPUT type="text" name="common[web_address]" size="25" value="{$entry.en.web_address}" /></TD>
 </TR>
 <TR>
  <TD class="wb">{#captionPhone#}:</TD>
  <TD><INPUT type="text" name="common[phone]" size="25" value="{$entry.en.phone}" /></TD>
 </TR>
 <TR>
  <TD class="wb">{#captionFax#}:</TD>
  <TD><INPUT type="text" name="common[fax]" size="25" value="{$entry.en.fax}" /></TD>
 </TR>
{/if}

 <tr>
  <td colspan="2">&nbsp;</td>
 </tr>

{/foreach}

 <TR><TD align="center" colspan="2"><INPUT type="submit" value="{#saveAction#}"></TD></TR>
</TABLE>

</FORM>