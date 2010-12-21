{strip}{assign var="ve_textareas" value=""}
{foreach from=$list_user_languages item="lang" name="lang_fe"}
{assign var="ve_textareas" value="`$ve_textareas`content_`$lang.code`"}
{if !$smarty.foreach.lang_fe.last}{assign var="ve_textareas" value="`$ve_textareas`,"}{/if}
{/foreach}{/strip}
{include file="common/orgContentVisualEdit.tpl" textarea=$ve_textareas}

<SCRIPT type="text/javascript" src="{$document_root}js/adminFormValidator.js"></SCRIPT>
<SCRIPT type="text/javascript" src="{$document_root}js/adminAutocomplete.js"></SCRIPT>
<SCRIPT type="text/javascript">

{foreach from=$list_user_languages item="lang"}
objAC_categories_{$lang.code} = Shelby_Backend.Autocomplete.cloneObject('objAC_categories_{$lang.code}');
objAC_categories_{$lang.code}.feedUrl = '{getUrl language=$lang.code controller="sab_jsonfeeds" action="compservcats"}';

objAC_categories_{$lang.code}.pickElement = function(i) {ldelim}
	var jsonElId = this.SelTagToJsonDOId[i];

	$('#objAC_categories_input_{$lang.code}').attr("value", this.jsonDataObj[jsonElId].name);

	this.hidePopUp();
{rdelim}
{/foreach}

objValidator = Shelby_Backend.FormValidator.cloneObject('objValidator');

objValidator.headerMessage = "{#msgThereAreErrors#}:\n\n";

objValidator.addField('brands_subcategories', 'num', 1, "{#msgSelectCategory#}\n");
objValidator.addField('name_{$user_language.code}', 'text', 3, "{#msgEnterName#}\n");
objValidator.addField('short_{$user_language.code}', 'text', 10, "{#msgEnterShortDescription#}\n");
objValidator.addField('content_{$user_language.code}', 'tinyMCE', 10, "{#msgEnterFullDescription#}\n");

function chBrandCategory(id) {ldelim}
	var url = "{getUrl controller="sab_jsonfeeds" action="brandssubcategories"}" + "parent/" + id + "/";
	{literal}
	if (id > 0) {
		$.getJSON(url, function(json) {
			$("#brands_subcategories").empty();
			$.each(json.list.data, function(i) {
				$("#brands_subcategories").append('<option value="' + json.list.data[i].id + '">' + json.list.data[i].name + '</option>');
			});
			$("#brands_subcategories").removeAttr("disabled");
		});
	} else {
		$("#brands_subcategories").empty();
		$("#brands_subcategories").attr("disabled", "disabled");
	}
	{/literal}
{rdelim}
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="insert"}" enctype="multipart/form-data" onsubmit="return objValidator.validate();" onkeypress="return event.keyCode!=13;">

<h4>{#hdrAddingNewProductService#}</h4>

<input type="button" value="{#linkBackToList#}" onClick="document.location.href='{getUrl add="1" action="list" del="id"}';"><br><br>

<TABLE border="0" width="100%" style="border-collapse:collapse;">
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
  <TD class="wb">{#captionImage#}:</TD>
  <TD><INPUT type="file" name="logo"></TD>
 </TR>
 <TR>
  <TD class="wb">{#captionCategory#} *:</TD>
  <TD>{* {#captionSelectCategory#}: *}
	<SELECT name="common[companies_services_cats_id]" style="width:80%;" onchange="chBrandCategory(this.value);">
    <OPTION value="">{#captionCategoryNotChosen#}</OPTION>
    {foreach from=$list_categories item="el"}
    <OPTION value="{$el.id}">{$el.name}</OPTION>
    {/foreach}
   </SELECT><BR />
   <SELECT name="common[brands_subcategories_id]" disabled="disabled" style="width:80%;" id="brands_subcategories">
    <OPTION value="">{#captionCategoryNotChosen#}</OPTION>
   </SELECT>

	{* {#captionCreateNewCategory#}:<BR />
   {foreach from=$list_user_languages item="langg"}
   {#captionName#} ({$langg.name}):
   <INPUT type="text" size="30" name="{$langg.code}[category_name]" id="objAC_categories_input_{$langg.code}"{-- * onKeyUp="objAC_categories_{$langg.code}.getData(this.value, event);"* --}>
   {-- *<div id="objAC_categories_{$langg.code}_popup_id" style="position:absolute; border: 1px solid #6f5d15; width:250px; background-color:white; visibility:hidden; float:left;"></div>* --}
   <BR />
   {/foreach} *}
  </TD>
 </TR>
 <TR>
  <TD class="wb">{#captionType#}:</TD>
  <TD>
   <SELECT name="common[type]">
    <OPTION value="product">{#captionTypeProduct#}</OPTION>
    <OPTION value="service">{#captionTypeService#}</OPTION>
   </SELECT>
  </TD>
 </TR>
 <TR class="wb">
  <TD>{#captionPrice#}:</TD>
  <TD><INPUT type="text" size="12" name="common[price]"> ({#captionFormat#} ######.##)</TD>
 </TR>
{/if}

 <TR>
  <TD class="wb">{#captionShortDescription#}{if $lang.id==$company_data.language_id}*{/if}:</TD>
  <TD><TEXTAREA name="{$lang.code}[short]" style="width:100%; height:100px;" id="short_{$lang.code}"></TEXTAREA></TD>
 </TR>
 <TR>
  <TD class="wb">{#captionFullDescription#}{if $lang.id==$company_data.language_id}*{/if}:</TD>
  <TD><TEXTAREA name="{$lang.code}[content]" id="content_{$lang.code}" style="width:100%; height:500px;"></TEXTAREA></TD>
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