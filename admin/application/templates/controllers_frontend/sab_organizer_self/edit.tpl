{include file="common/contentVisualEdit.tpl" textarea="description_ru,description_en" imagesDefaultParent="organizers"}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT type="text/javascript" language="javascript">

  objCitiesList = Shelby_Backend.ListHelper.cloneObject('objCitiesList');

  objCitiesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('country_name', 'Страна'));
  objCitiesList.returnFieldId = 'cities_id';
  objCitiesList.feedUrl  = '{getUrl controller="sab_jsonfeeds" action="cities"}';
  objCitiesList.valueUrl = '{getUrl controller="sab_jsonfeeds" action="city"}';
  objCitiesList.writeForm();

  {literal}
  objCitiesList.renderName = function(entry) {
    return  entry.country_name + ', ' + entry.name;
  }
  {/literal}

  objCitiesList.getValue('{$entry.cities_id}');

  {literal}
  function changeLanguage(code) {
    {/literal}{foreach from=$list_languages item="lang"}
    $("#data_top_{$lang.code}").hide();
    {/foreach}{literal}
    $("#data_top_" + code).show();

    $("#langbar td").removeClass('active');
    $("#langbar #langtab-" + code).addClass('active');
  }


  $(function () {
    changeLanguage('{/literal}{$selected_language}{literal}');
  });
  {/literal}
</SCRIPT>

<form method="post" action="{getUrl add="1" action="update"}">

<table border="0" width="100%" class="form-table">
<tr>
<tr>
  <td>{#captionCity#}:</td>
  <td>
    <input type="button" onclick="objCitiesList.showPopUp();" value=" {#chooseAction#} " /> <span id="cities_id_name">{$entry[$selected_language].city_name}</span>
    <input type="hidden" name="common[cities_id]" id="cities_id" value="{$entry[$selected_language].cities_id}" />
  </td>
</tr>
<tr>
  <td>{#captionSocialOrganizations#}:</td>
  <td>
  <div style="overflow:auto; height:250px;">
    {foreach from=$list_socorgs item="el"}
    <input type="checkbox" name="so[{$el.id}]" value="{$el.id}"{if isset($entry.socorgs[$el.id])} checked{/if} /> {$el.name}<br />
    {/foreach}
  </div>
  </td>
</tr>

</tr>
</table>


{include file="common/editor-langbar.tpl"}


{foreach from=$list_languages item=lang}
<table border="0" width="100%" class="form-table" id="data_top_{$lang.code}">
<tr>
  <td>{#captionName#}:</td>
  <td><input type="text" size="80" name="{$lang.code}[name]" value="{$entry[$lang.code].name}"></td>
</tr>

<tr>
  <td colspan="2">{#desc#}:<br />
  <textarea name="{$lang.code}[description]" id="description_{$lang.code}" style="width:95%; height:500px;">{$entry[$lang.code].description|escape:"html"}</textarea>
  </td>
</tr>

<tr>
  <td colspan="2" valign="top">

    <fieldset style="width:45%; border-collapse:collapse; float:left;">
      <legend>{#captionContacts#}:</legend>

      <table width="100%">
      <tr>
        <td>{#address#}: </td>
        <td><input type="text" name="{$lang.code}[address]" value="{$entry[$lang.code].address}" size="25"></td>
      </tr>
      <tr>
        <td>{#captioZipcode#}: </td>
        <td><input type="text" name="{$lang.code}[postcode]" value="{$entry[$lang.code].postcode}" size="25"></td>
      </tr>
      <tr>
        <td>{#email#}: </td>
        <td><input type="text" name="{$lang.code}[email]" value="{$entry[$lang.code].email}" size="25"></td>
      </tr>
      <tr>
        <td>{#webPage#}: </td>
        <td><input type="text" name="{$lang.code}[web_address]" value="{$entry[$lang.code].web_address}" size="25"></td>
      </tr>
      <tr>
        <td>{#phone#}: </td>
        <td><input type="text" name="{$lang.code}[phone]" value="{$entry[$lang.code].phone}" size="25"></td>
      </tr>
      <tr>
        <td>{#fax#}: </td>
        <td><input type="text" name="{$lang.code}[fax]" value="{$entry[$lang.code].fax}" size="25"></td>
      </tr>
      </table>
    </fieldset>
  </TD>
</TR>
</table>
{/foreach}

<p style="text-align:center"><input type="submit" value=" {#updateAction#} "></p>

</form>

{if $smarty.get.debug}{debug}{/if}