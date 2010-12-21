<h1 class="h-main">{#register#}</h1>

<script type="text/javascript">
objValidator = Shelby_Backend.FormValidator.cloneObject('objValidator');

objValidator.headerMessage = "{#msgOrgRegError#}:\n\n";
objValidator.addField('login',    'text',  3, "- {#msgOrgRegLoginError#}\n");
objValidator.addField('passwd',   'text',  3, "- {#msgOrgRegPasswordError#}\n");
objValidator.addField('name',     'text',  3, "- {#msgOrgRegNameError#}\n");
objValidator.addField('position', 'text',  3, "- {#msgOrgRegPositionError#}\n");
objValidator.addField('company',  'text',  3, "- {#msgOrgRegCompanyError#}\n");
objValidator.addField('email',    'email', 3, "- {#msgOrgRegEmailError#}\n");
objValidator.addField('country',  'text',  1, "- {#msgOrgRegCountryError#}\n");
objValidator.addField('city',     'text',  1, "- {#msgOrgRegCityError#}\n");
// objValidator.addField('agree',    'checkbox',  1, "- Подтвердите что ознакомились нашим Соглашением\n");
</script>

<div style="width:75%">

{if $last_action_result=="OK"}
<p class="form-success">{#RegistrationSuccess#}</p>
<p style="padding-left:15px;">{#RegistrationSuccessMessage#}</p>
{else}

{if $last_action_result == "WRONG_EMAIL"}
 <p class="form-error">{#ErrorWrongEmail#}</p>
{elseif $last_action_result == "LOGIN_EXISTS"}
 <p class="form-error">{#ErrorLoginExists#}</p>
{elseif $last_action_result == "FAILED"}
 <p class="form-error">{#ErrorUnknown#}</p>
{elseif $last_action_result == "WRONG_CAPTCHA"}
 <p class="form-error">{#ErrorCaptcha#}</p> 
{/if}

<form method="post" onsubmit="return objValidator.validate();">

	<table cellspacing="0">
	<colgroup>
		<col width="180" />
		<col width="*" />
	</colgroup>

	<tr>
		<td><strong>{#login#} *:</strong></td>
		<td><input type="text" id="login" name="login" value="{$smarty.post.login}" style="width:30%" /></td>
	</tr>

	<tr>
		<td><strong>{#pswd#} *:</strong></td>
		<td><input type="password" id="passwd" name="passwd" value="{$smarty.post.passwd}" style="width:30%" /></td>
	</tr>

	<tr>
		<td><strong>{#name#} *:</strong></td>
		<td><input type="text" id="name" name="name_fio" value="{$smarty.post.name_fio}" style="width:98%" /> </td>
	</tr>

	<tr>
		<td><strong>{#position#} *:</strong></td>
		<td><input type="text" id="position" name="position" value="{$smarty.post.position}" style="width:98%" /></td>
	</tr>

	<tr>
		<td><strong>{#captionCompany#} *:</strong></td>
		<td><input type="text" id="company" name="organizer_manual_name" value="{$smarty.post.organizer_manual_name}" style="width:50%" /></td>
	</tr>

	<tr>
		<td><strong>{#email#} *:</strong></td>
		<td><input type="text" id="email" name="email" value="{$smarty.post.email}" /></td>
	</tr>

	<tr>
		<td><strong>{#phone#} *:</strong></td>
		<td><input type="text" id="phone" name="phone" value="{$smarty.post.phone}" /></td>
	</tr>

	<tr>
		<td><strong>{#country#} *:</strong></td>
		<td>
		  <select id="country" name="country" style="width:51%">
		<option value="">{#filterCountryDefault#}</option>
		{foreach from=$list_countries.data item="el"}
		<option value="{$el.name}"{if $el.name==$smarty.post.country} selected="selected"{/if}>{$el.name}</option>
		{/foreach}
		  </select>
		</td>
	</tr>

	<tr>
		<td><strong>{#city#} *:</strong></td>
		<td><input type="text" id="city" name="city" value="{$smarty.post.city}" style="width:50%" /></td>
	</tr>

	<tr>
		<td><strong>{#site#}:</strong></td>
		<td><input type="text" name="url" value="{$smarty.post.url}" /></td>
	</tr>
	
<tr valign="top">
  <td style="vertical-align:middle;"><strong>{#Captcha#} *:</strong></td>
  <td><input class="captcha-field" type="text" name="captcha" size="5"/><img class="captcha" src="/img_checker.php"/></td>
</tr>	

	<tr>
		<td colspan="2">
			<input type="submit" value=" {#registerAction#} " />
		</td>
	</tr>
	</table>

</form>
{/if}

</div>