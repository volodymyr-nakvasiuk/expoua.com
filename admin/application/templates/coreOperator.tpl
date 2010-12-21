{config_load file=global.conf}<HTML>
 <HEAD>
  <TITLE>Exhibition Global Marketing System</TITLE>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <LINK rel="stylesheet" href="{$document_root}css/admin/admin_style.css" type="text/css" media="screen" />
  <LINK rel="stylesheet" href="{$document_root}css/admin/jqModal.css" type="text/css" media="screen" />
  <LINK rel="stylesheet" href="{$document_root}css/admin/ui.datepicker.css" type="text/css" media="screen" />
  <SCRIPT type="text/javascript" src="{$document_root}js/adminGeneral.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/adminAutocomplete.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/jquery.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/jqExtensions/jqModal.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/jqExtensions/ui.datepicker.js"></SCRIPT>
 </HEAD>
 <BODY id="cms-wrapper">

<TABLE width="100%" align="center" cellspacing="0" border="0" style="position:static;">
 <TR>
  <TD align="left"><span class="blue_hilight">Вы вошли как: {$session_user} [<a href="{getUrl controller="sab_operator_auth" action="logout"}">Выйти</a>]</span></TD>
  <TD align="right">{include file="common/listLanguages.tpl"}</TD>
 </TR>
</TABLE>

<CENTER>
 <A class="leftMenuSubElement" href="{getUrl controller="sab_operator_brandsevents" action="list"}">Бренд+Событие</A> &nbsp; &nbsp; &nbsp;
 <A class="leftMenuSubElement" href="{getUrl controller="sab_operator_drafts" action="list"}">Черновики</A> &nbsp; &nbsp; &nbsp;
 <A class="leftMenuSubElement" href="{getUrl controller="sab_operator_drafts" action="add"}">Добавить новый бренд+событие</A> &nbsp; &nbsp; &nbsp;
 <A class="leftMenuSubElement" href="{getUrl controller="sab_operator_brands" action="list"}">Все бренды</A> &nbsp; &nbsp; &nbsp;
 <A class="leftMenuSubElement" href="{getUrl controller="sab_operator_organizers" action="list"}">Все организаторы</A> &nbsp; &nbsp; &nbsp;
{* <A class="leftMenuSubElement" href="{getUrl controller="sab_operator_log" action="list"}">Журнал работы</A> *}
</CENTER>

{include file="controllers_frontend/`$user_params.controller`/`$user_params.action`.tpl"}

{if $smarty.get.debug}{debug}{/if}

</BODY>
</HTML>