<HTML>
 <HEAD>
  <TITLE>Exhibition Global Marketing System</TITLE>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8">
  <LINK rel="stylesheet" href="{$document_root}css/admin/admin_style.css" type="text/css">
 </HEAD>
{literal}
<STYLE type="text/css">
HTML, BODY {
	height: 98%;
}
</STYLE>
{/literal}
 <BODY id="cms-wrapper">

<TABLE width="100%" align="center" cellspacing="0" border="0" style="position:static;">
 <TR>
  <TD align="right" valign="top"><img src="{$document_root}images/admin/logo.gif"></TD>
 </TR>
</TABLE>
<TABLE height="100%" width="100%" border="0" cellpadding="8">
<TR><TD>
 <P>&nbsp;</P><P>&nbsp;</P><P>&nbsp;</P>

 {if isset($last_action_result)}
 <CENTER>
  {if $last_action_result==1}
   {if $user_params.action=='logout'}
    Вы успешно вышли
   {else}
    Вы успешно вошли
<SCRIPT type="text/javascript">
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
</SCRIPT>
   {/if}
  {elseif $last_action_result==-1}
   Такого пользователя не существует
  {elseif $last_action_result==-3}
   Введен неверный пароль
  {/if}
 </CENTER>
 {/if}

 <P>&nbsp;</P>

 <FORM method="post" action="{getUrl add="1" action="login"}">

<TABLE align="center" cellspacing="0" border="0">
 <TR>
  <TH colspan="2" align="center">{if $user_params.controller == 'sab_organizer_auth'}
  Организаторам выставок
 {else}
  Authentification
{/if}</TH>
 </TR>
 <TR>
  <TD>Логин: </TD><TD><INPUT type="text" size="20" name="login"></TD>
 </TR>
 <TR>
  <TD>Пароль: </TD><TD><INPUT type="password" name="passwd"></TD>
 </TR>
 <TR>
  <TD colspan="2" align="center"><INPUT type="submit" value="Ok"></TD>
 </TR>
</TABLE>

</FORM>
</TD></TR>
<TR><TD height="100%" valign="bottom" align="right">
 <!-- <a href="http://framework.zend.com/" target="zfwindow"><img src="http://framework.zend.com/images/PoweredBy_ZF_4LightBG.png" border="0"></a> -->
</TD></TR>
</TABLE>

</BODY>
</HTML>