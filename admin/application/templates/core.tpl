{config_load file=global.conf}<HTML>
 <HEAD>
  <TITLE>Exhibition Global Marketing System</TITLE>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <LINK rel="stylesheet" href="{$document_root}css/admin/admin_style.css" type="text/css" media="screen" />
  <LINK rel="stylesheet" href="{$document_root}css/admin/jqModal.css" type="text/css" media="screen" />
  <LINK rel="stylesheet" href="{$document_root}css/admin/ui.datepicker.css" type="text/css" media="screen" />
  <SCRIPT type="text/javascript" src="{$document_root}js/adminGeneral.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/adminAutocomplete.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/jquery.js"></SCRIPT -->
  <!-- script src="http://cdn.jquerytools.org/1.0.2/jquery.tools.min.js"></script -->
  <SCRIPT type="text/javascript" src="{$document_root}js/jqExtensions/jqModal.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/jqExtensions/ui.datepicker.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/jqExtensions/jquery.accordion.js"></SCRIPT>
 </HEAD>
 <BODY id="cms-wrapper">

<TABLE width="100%" align="center" cellspacing="0" border="0" style="position:static;">
 <TR>
  <TD align="left"><span class="blue_hilight">Вы вошли как: {$session_user} [<a href="{getUrl controller="admin_auth" action="logout"}">Выйти</a>]</span></TD>
  <TD align="right">{include file="common/listLanguages.tpl"}</TD>
 </TR>
</TABLE>
<br/>
<TABLE width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
 <TR>
  <TD valign="top" id="shelby_left_menu_td" width="{if !empty($smarty.cookies.shelby_left_menu) && $smarty.cookies.shelby_left_menu=="hide"}10{else}218{/if}">
   {include file="common/leftMenu.tpl"}
  </TD>
  <td width="6" valign="top" style="padding-top:100px;"><a href="#" onclick="Shelby_Backend.leftMenuHS();"><img src="{$document_root}images/admin/hide_button.gif" width="6" border="0"></a></td>
  <TD valign="top" id="shelby_content_td">
  {include file="controllers/`$user_params.controller`/`$user_params.action`.tpl"}
  </TD>
 </TR>
</TABLE>

{if $smarty.get.debug}{debug}{/if}

</BODY>
</HTML>