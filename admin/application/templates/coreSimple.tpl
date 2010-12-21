{config_load file="file:global.conf"}
{config_load file="file:`$user_params.controller`.conf"}
<HTML>
 <HEAD>
  <TITLE>Exhibition Global Marketing System</TITLE>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <STYLE>
	body, p, td {ldelim}
		font-family: Tahoma, Arial, sans-serif;
		font-size: 9pt;
	{rdelim}
  </STYLE>

 </HEAD>
 <BODY>

<DIV>
{include file="file:controllers_frontend/`$user_params.controller`/`$user_params.action`.tpl"}
</DIV>

 </BODY>
</HTML>