<script type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></script>

<script>
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
</script>

<form method="post" action="{getUrl add="1" action="update"}" enctype="multipart/form-data">

<table border="0" width="100%" class="form-table">
<colgroup>
  <col width="10%" />
  <col width="37%" />
  <col width="*" />
  <col width="10%" />
  <col width="37%" />
</colgroup>

<tr valign="top">
  <td>{#captionName#}:</td>
  <td><input type="text" size="50" name="name" value="{$entry.name}" style="width:100%;" tabindex="1" /></td>
  <td>&nbsp;</td>
  <td>{#login#}:</td>
  <td><input type="text" size="20" name="login" value="{$entry.login}" readonly="readonly" tabindex="2" /></td>
</tr>

<tr valign="top">
  <td>{#captionLocation#}:</td>
  <td>
    <input type="button" onclick="objCitiesList.showPopUp();" value=" {#chooseAction#} " tabindex="4" />
    <span id="cities_id_name">{$entry.city_name}</span>
    <input type="hidden" name="cities_id" id="cities_id" value="{$entry.cities_id}" />
  </td>

  <td>&nbsp;</td>

  <td>{#pswd#}:</td>
  <td><input type="password" size="20" name="passwd" value="" tabindex="3" /></td>
</tr>

<tr valign="top">
  <td>{#fax#}:</td>
  <td><input type="text" name="fax" value="{$entry.fax}" size="25" style="width:100%;" tabindex="5" /></td>

  <td>&nbsp;</td>

  <td>&nbsp;</td>
  <td>{#msgPasswordExplanation#}</td>
</tr>

<tr valign="top">
  <td>{#address#}:</td>
  <td>
   <textarea name="address" style="width:100%; height:50px;" tabindex="6">{$entry.address}</textarea>
  </td>

  <td>&nbsp;</td>

  <td>{#captionDetails#}:</td>
  <td>
   <textarea name="details" style="width:100%; height:50px;" tabindex="7">{$entry.details}</textarea>
  </td>
</tr>

<tr valign="top">
  <td colspan="2">
    <fieldset>
      <legend>{#captionContactPerson#}</legend>

      <table border="0" width="100%" style="border-collapse:collapse;" class="form-table">
      <colgroup>
        <col width="20%" />
        <col width="*" />
      </colgroup>

      <tr valign="top">
        <td>{#name#}:</td>
        <td><input type="text" size="50" name="contact_name" value="{$entry.contact_name}" style="width:100%;" tabindex="8" /></td>
      </tr>

      <tr valign="top">
        <td>{#email#}:</td>
        <td><input type="text" size="30" name="contact_email" value="{$entry.contact_email}" style="width:100%;" tabindex="9" /></td>
      </tr>

      <tr valign="top">
        <td>{#phone#}:</td>
        <td><input type="text" size="30" name="contact_phone" value="{$entry.contact_phone}" style="width:60%;" tabindex="10" /></td>
      </tr>

      <tr valign="top">
        <td>{#cellphone#}:</td>
        <td><input type="text" size="30" name="contact_cell" value="{$entry.contact_cell}" style="width:60%;" tabindex="11" /></td>
      </tr>

      <tr valign="top">
        <td>{#captionIM#}:</td>
        <td><input type="text" size="30" name="contact_im" value="{$entry.contact_im}" style="width:60%;" tabindex="12" /></td>
      </tr>

      <tr valign="top">
        <td>{#captionIMtype#}:</td>
        <td>
          <select name="contact_imtype" style="width:60%;" tabindex="13">
            <option value="ICQ"{if $entry.contact_imtype == 'ICQ'} selected="selected"{/if}>ICQ</option>
            <option value="AIM"{if $entry.contact_imtype == 'AIM'} selected="selected"{/if}>AIM</option>
            <option value="MSN"{if $entry.contact_imtype == 'MSN'} selected="selected"{/if}>MSN</option>
            <option value="Jabber"{if $entry.contact_imtype == 'Jabber'} selected="selected"{/if}>Jabber</option>
            <option value="Skype"{if $entry.contact_imtype == 'Skype'} selected="selected"{/if}>Skype</option>
          </select>
        </td>
      </tr>
      </table>
    </fieldset>
  </td>

  <td>&nbsp;</td>

  <td colspan="2">
    <fieldset>
      <legend>{#captionTechPerson#}</legend>

      <table border="0" width="100%" style="border-collapse:collapse;" class="form-table">
      <colgroup>
        <col width="20%" />
        <col width="*" />
      </colgroup>

      <tr valign="top">
        <td>{#name#}:</td>
        <td><input type="text" size="50" name="tech_name" value="{$entry.tech_name}" style="width:100%;" tabindex="14" /></td>
      </tr>

      <tr valign="top">
        <td>{#phone#}:</td>
        <td><input type="text" size="30" name="tech_phone" value="{$entry.tech_phone}" style="width:60%;" tabindex="15" /></td>
      </tr>

      <tr valign="top">
        <td>{#cellphone#}:</td>
        <td><input type="text" size="30" name="tech_cell" value="{$entry.tech_cell}" style="width:60%;" tabindex="16" /></td>
      </tr>

      <tr valign="top">
        <td>{#captionIM#}:</td>
        <td><input type="text" size="30" name="tech_im" value="{$entry.tech_im}" style="width:60%;" tabindex="17" /></td>
      </tr>

      <tr valign="top">
        <td>{#captionIMtype#}:</td>
        <td>
          <select name="tech_imtype" style="width:60%;" tabindex="18">
            <option value="ICQ"{if $entry.tech_imtype == 'ICQ'} selected="selected"{/if}>ICQ</option>
            <option value="AIM"{if $entry.tech_imtype == 'AIM'} selected="selected"{/if}>AIM</option>
            <option value="MSN"{if $entry.tech_imtype == 'MSN'} selected="selected"{/if}>MSN</option>
            <option value="Jabber"{if $entry.tech_imtype == 'Jabber'} selected="selected"{/if}>Jabber</option>
            <option value="Skype"{if $entry.tech_imtype == 'Skype'} selected="selected"{/if}>Skype</option>
          </select>
        </td>
      </tr>
      </table>
    </fieldset>
  </td>
</tr>

<tr>
  <td align="center" colspan="5"><input type="submit" value=" {#updateAction#} " tabindex="19" /></td>
</tr>
</table>

</form>