<h1 class="h-main">{#msgOrganizerAuth#}<h1>

{if isset($last_action_result)}
  {if $last_action_result==1}
	<p class="form-success" style="text-align:center;">
	{if $user_params.action=='logout'}
	  {#AuthLogOutSuc#}
	{else}
	  {#AuthLogInSuc#}
	  <script type="text/javascript">
	  setTimeout("redir()", 200);
	  function redir() {ldelim}
		{if $user_params.controller == 'admin_auth'}
		  document.location.href="{getUrl controller="admin_index" action="index"}";
		{elseif $user_params.controller == 'sab_operator_auth'}
		  document.location.href="{getUrl controller="sab_operator_index" action="index"}";
		{elseif $user_params.controller == 'sab_organizer_auth'}
		  document.location.href="{getUrl controller="sab_organizer_index" action="index"}";
		{/if}
	  {rdelim}
	  </script>
	  <span class="block" style="margin:5px 0 0;"><img src="/images/admin/loadingAnimation.gif" alt="Wait..." /></span>
	</p>
	{/if}
	{elseif $last_action_result==-1}
        <p class="form-error" style="text-align:center;">{#AuthUserNotExist#}</p>
      {elseif $last_action_result==-3}
        <p class="form-error" style="text-align:center;">{#AuthPswdIncorrect#}</p>
      {/if}
{/if}

<div id="filter" style="margin:0 auto; width:420px;">
  <form id="loginForm" method="post" action="{getUrl add="1" action="login"}">

  <table align="center" cellspacing="0" border="0">
  <colgroup width="170"></colgroup>
  <colgroup width="*"></colgroup>
  <tr>
	<td>{#login#}: </td><td><input type="text" size="20" id="_login" name="login" style="width:170px"></td>
  </tr>

  <tr>
	<td>{#pswd#}: </td><td><input type="password" id="_password" name="passwd" style="width:170px"></td>
  </tr>
  <tr>
	<td colspan="2" style="padding-left:177px;"><label><input type="checkbox" name="remember" style="margin-left:0; vertical-align:middle;"/>{#rememberMe#}</label></td>
  </tr>
  </table>
  <div style="padding-left:177px;"><button type="submit"> OK </button></div>

  </form>
</div>