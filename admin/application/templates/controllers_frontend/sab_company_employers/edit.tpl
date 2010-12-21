<SCRIPT type="text/javascript" src="{$document_root}js/adminFormValidator.js"></SCRIPT>

<SCRIPT type="text/javascript">
objValidator = Shelby_Backend.FormValidator.cloneObject('objValidator');

objValidator.headerMessage = "{#msgThereAreErrors#}:\n\n";

objValidator.addField('name_{$user_language.code}', 'text', 2, "{#msgEnterFirstName#}\n");
objValidator.addField('lastname_{$user_language.code}', 'text', 2, "{#msgEnterLastName#}\n");
objValidator.addField('position_{$user_language.code}', 'text', 3, "{#msgEnterPosition#}\n");

objValidator.addField('email_id', 'email', 0, "{#msgEnterEmail#}\n");
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="update"}" enctype="multipart/form-data" onsubmit="return objValidator.validate();">
<INPUT type="hidden" name="common[dummy]" value="1" />
<h4>{#hdrCompanyEmployeeEditing#}</h4>

<input type="button" value="{#linkBackToList#}" onClick="document.location.href='{getUrl add="1" action="list" del="id"}';"><br><br>

{if $entry.en.photo==1} <img src="/data/images/companies/{$entry.en.companies_id}/employers/{$entry.en.id}.jpg">{/if}

<TABLE border="0" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD class="wb" width="200">{#captionPhoto#}:</TD>
  <TD>
   <INPUT type="file" name="logo">
   {if $entry.en.photo==1} <a href="/data/images/companies/{$entry.en.companies_id}/employers/{$entry.en.id}.jpg" target="_blank">{#previewAction#}</a>{/if}
  </TD>
 </TR>
 
 <tr>
  <td colspan="2">&nbsp;</td>
 </tr>
 
 {foreach from=$list_user_languages item="lang"}
 <tr>
  <td colspan="2"><h5>{$lang.name}</h5></td>
 </tr>
 <TR>
  <TD class="wb">{#captionFirstName#}{if $lang.id==$company_data.language_id}*{/if}:</TD>
  <TD><INPUT type="text" size="40" name="{$lang.code}[name]" value="{$entry[$lang.code].name|escape:"html"}" id="name_{$lang.code}"></TD>
 </TR>
 <TR>
  <TD class="wb">{#captionLastName#}{if $lang.id==$company_data.language_id}*{/if}:</TD>
  <TD><INPUT type="text" size="40" name="{$lang.code}[lastname]" value="{$entry[$lang.code].lastname|escape:"html"}" id="lastname_{$lang.code}"></TD>
 </TR>
 <TR>
  <TD class="wb">{#captionPosition#}{if $lang.id==$company_data.language_id}*{/if}:</TD>
  <TD><INPUT type="text" size="40" name="{$lang.code}[position]" value="{$entry[$lang.code].position|escape:"html"}" id="position_{$lang.code}"></TD>
 </TR>
{if $lang.id==$company_data.language_id}
 <TR>
  <TD class="wb">{#captionEmail#}*:</TD>
  <TD><INPUT type="text" size="40" name="common[email]" value="{$entry.en.email}" id="email_id"></TD>
 </TR>
 <TR>
  <TD class="wb">{#captionPhone#}:</TD>
  <TD><INPUT type="text" size="40" name="common[phone]" value="{$entry.en.phone}"></TD>
 </TR>
{/if}
 <tr>
  <td colspan="2">&nbsp;</td>
 </tr>
 {/foreach}

 <TR><TD align="center" colspan="2"><BR /><INPUT type="submit" value="{#saveAction#}"></TD></TR>
</TABLE>

</FORM>