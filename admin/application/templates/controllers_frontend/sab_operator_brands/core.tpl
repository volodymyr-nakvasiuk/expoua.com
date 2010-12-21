{config_load file=global.conf}<HTML>
 <HEAD>
  <TITLE>Exhibition Global Marketing System</TITLE>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <LINK rel="stylesheet" href="{$document_root}css/admin/admin_style.css" type="text/css" media="screen" />
  <SCRIPT type="text/javascript" src="{$document_root}js/adminGeneral.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/jquery.js"></SCRIPT>
 </HEAD>
 <BODY id="cms-wrapper">

{include file="controllers_frontend/`$user_params.controller`/`$user_params.action`.tpl"}

{if $smarty.get.debug}{debug}{/if}

</BODY>
</HTML>