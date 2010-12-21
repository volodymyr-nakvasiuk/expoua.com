<div class="login-popup none">
<div class="bg title">
	<h5 class="h">{#orgAuthEntrance#}</h5>
	<a id="loginCloser" class="bg ClosePopUp" title="{#close#}"><!-- --></a>
</div>
<div class="content">
	<form id="loginForm" action="{$formAction}" method="post">
		{if ($user_params.controller == 'sab_partner_info') || ($user_params.controller == 'sab_partner_auth')}
		<input type="hidden" name="c" value="sab_partner_self" />
		<input type="hidden" name="a" value="edit" />
		{/if}
		<table cellspacing="0">
		<tr>
			<td class="FormLabel">{#login#}:</td>
			<td><input type="text" size="20" id="_login" name="login" /></td>
		</tr>
		<tr>
			<td>{#pswd#}:</td>
			<td><input type="password" size="20" id="_password" name="passwd" /></td>
		</tr>
		<tr>
			<td align="left" colspan="2"><span class="remember"><label><input type="checkbox" value="1" name="remember" class="checkbox" />Запомнить меня</label></span></td>
		</tr>
	</table>
	<div class="submit" style="margin:0 0 14px;"><input type="submit" value="{#orgAuthLogin#}"></div>
	<p class="links"><a href="{$registerURL}">{#RegisterLinkTitle#}</a>, {#IfYouHaveNoAccount#}.</p>
	{*<p class="links remind"><a href="" title="{#jsonRemindPassword#}"></a></p>*}
	</form>
</div>
<script type="text/javascript">
<!--
{literal}
	$('#loginOpener').click(function(){
		$('.login-popup').toggleClass('none');
		return false;
	});
	$('#loginCloser').click(function(){
		$('.login-popup').toggleClass('none');
		return false;
	});
{/literal}//-->
</script>
</div>