{strip}{assign var="ve_textareas" value=""}
{foreach from=$list_languages item="lang" name="lang_fe"}
{assign var="ve_textareas" value="`$ve_textareas`additional_info_`$lang.code`"}
{if !$smarty.foreach.lang_fe.last}{assign var="ve_textareas" value="`$ve_textareas`,"}{/if}
{/foreach}{/strip}
{include file="common/orgContentVisualEdit.tpl" textarea=$ve_textareas}

<script type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></script>

<script>
  objSocOrgsList = Shelby_Backend.ListHelper.cloneObject('objSocOrgsList');
  objCitiesList = Shelby_Backend.ListHelper.cloneObject('objCitiesList');
  
  objSocOrgsList.columns = new Array(new Array('name', 'Название'), new Array('city_name', 'Город'));
  objSocOrgsList.returnFieldId = 'social_organizations_id';
  objSocOrgsList.feedUrl = '{getUrl controller="sab_jsonfeeds" action="servcategories"}';
  objSocOrgsList.writeForm();
  
  objCitiesList.columns = new Array(new Array('name', 'Город'), new Array('country_name', 'Страна'));
  objCitiesList.returnFieldId = 'cities_id';
  objCitiesList.feedUrl = '{getUrl controller="sab_jsonfeeds" action="cities" sort="name:ASC"}';
  objCitiesList.writeForm();
</script>

<h4>{#EditSelfInfo#}</h4>

<form method="post" action="{getUrl add="1" action="update"}" enctype="multipart/form-data" id="self_form">

<table border="0" width="100%" style="border-collapse:collapse;" cellpadding="5">
<tr>
  <td class="wb">{#OrgSelect#}:</td>
  <td><input type="hidden" name="common[social_organizations_id]" id="social_organizations_id" value="{$entry[$selected_language].social_organizations_id}"> <input type="button" onclick="objSocOrgsList.showPopUp();" value="Выбрать"> <SPAN id="social_organizations_id_name">{$entry[$selected_language].social_organization_name}</SPAN></td>
</tr>

<tr>
  <td class="wb">{#Category#}:</td>
  <td>
    <select name="common[service_companies_categories_id]">
    {foreach from=$list_service_companies_cats item="el"}
      <option value="{$el.id}"{if $entry[$selected_language].service_companies_categories_id==$el.id} selected{/if}>{$el.name}</option>
    {/foreach}
    </select>
  </td>
</tr>

<tr>
  <td class="wb">{#City#}:</td>
  <td>
    <input type="hidden" name="common[cities_id]" id="cities_id" value="{$entry[$selected_language].cities_id}"> <input type="button" onclick="objCitiesList.showPopUp();" value="Выбрать"> <SPAN id="cities_id_name">{$entry[$selected_language].city_name}</SPAN>
  </td>
</tr>

<tr>
  <td class="wb">{#Email_requests#}:</td>
  <td>
    <input type="text" name="common[email_requests]" value="{$entry[$selected_language].email_requests}" size="40">
  </td>
</tr>


{*========================logo===================================*}
<input type="hidden" name="dummy" value="dummyvalue" />
{foreach from=$list_languages item="lang"}
<tr>
  <td class="wb">Логотип ({$lang.name}):</td>
  <td>
    <input type="file" name="logo[{$lang.code}]" />
    {if $entry[$lang.code].logo==1}&nbsp;&nbsp;&nbsp;<img src="/data/images/service_companies/logo/{$lang.id}/{$entry[$lang.code].id}.jpg" alt="" align="middle" />{else}Не загружен{/if}
  </td>
</tr>
{/foreach}
{*==========================logo_end=================================*}



{foreach from=$list_languages item="lang"}
<tr>
  <td class="wb">{#Name#} ({$lang.name}):</td>
  <td><input type="text" size="50" name="{$lang.code}[name]" value="{$entry[$lang.code].name}"></td>
</tr>
{/foreach}

{*
{foreach from=$list_languages item="lang"}
 <tr>
  <td class="wb">{#Description#} ({$lang.name}):</td>
  <td> <TEXTAREA name="{$lang.code}[content]" style="width:95%; height:50px;">{$entry[$lang.code].description}</TEXTAREA></td>
  </td>
 </tr>
{/foreach}

{foreach from=$list_languages item="lang"}
 <tr>
  <td class="wb">{#Activities#} ({$lang.name}):</td>
   <td><TEXTAREA name="{$lang.code}[activity_forms]" style="width:95%; height:50px;">{$entry[$lang.code].activity_forms}</TEXTAREA></td>
  </td>
 </tr>
 {/foreach}
*}

{foreach from=$list_languages item="lang"}
<tr>
  <td class="wb">{#Additional_info#} ({$lang.name}):</td>
  <td><TEXTAREA name="{$lang.code}[additional_info]" id="additional_info_{$lang.code}" style="width:95%; height:400px;">{$entry[$lang.code].additional_info|escape:"html"}</TEXTAREA></td>
</tr>
{/foreach}

{foreach from=$list_languages item="lang"}
<tr>
  <td class="wb">{#Contacts#} ({$lang.name}):</td>

  <td>
    <table border="0" width="45%" style="border-collapse:collapse; float:left;">
    <tr>
     <td>&raquo;&nbsp;Email: </td>
     <td><input type="text" name="{$lang.code}[email]" value="{$entry[$lang.code].email}" size="25"></td>
    </tr>
    <tr>
     <td>&raquo;&nbsp;{#Web_page#}: </td>
     <td><input type="text" name="{$lang.code}[web_address]" value="{$entry[$lang.code].web_address}" size="25"></td>
    </tr>
    <tr>
     <td>&raquo;&nbsp;{#Phone#}: </td>
     <td><input type="text" name="{$lang.code}[phone]" value="{$entry[$lang.code].phone}" size="25"></td>
    </tr>
    <tr>
     <td>&raquo;&nbsp;{#Fax#}: </td>
     <td><input type="text" name="{$lang.code}[fax]" value="{$entry[$lang.code].fax}" size="25"></td>
    </tr>
    <tr>
     <td>&raquo;&nbsp;{#Address#}: </td>
     <td><input type="text" name="{$lang.code}[address]" value="{$entry[$lang.code].address}" size="25"></td>
    </tr>
    <tr>
     <td>&raquo;&nbsp;{#Index#}: </td>
     <td><input type="text" name="{$lang.code}[postcode]" value="{$entry[$lang.code].postcode}" size="25"></td>
    </tr>
    </table>
  </td>
</tr>
{/foreach}

<tr>
 	<td align="right"><input type="button" value="{#Preview#}" onclick="window.open('http://www.expopromoter.com/Suppliers/lang/{$selected_language}/servcomp/{$entry[$selected_language].id}/');" /></td>
	<td align="left"><input type="submit" value="{#Update#}"></td>
</tr>
</table>

</form>
