{config_load file=global.conf}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Exhibition Global Marketing System</title>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <link rel="stylesheet" href="{$document_root}css/admin/layout.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="{$document_root}css/admin/colors.css" type="text/css" media="screen" />
  <script type="text/javascript" src="{$document_root}js/admingeneral.js"></script>
  <script type="text/javascript" src="{$document_root}js/adminautocomplete.js"></script>
  <script type="text/javascript" src="{$document_root}js/jquery.js"></script>
</head>

<body>
<div class="main" id="main">
	<div class="header bg">
		<div class="body floatfix">
			<a class="logo" href="http://expopromoter.com/{$selected_language}/"><!-- --><span class="title">ExpoPromoter.com &mdash; {#msgTopTitle#}{*#msgStatTopOn#*}</span></a>
			<a class="sub-logo" href="/{$selected_language}/"></a>
			<div class="login floatfix">
				{if !$session_user}
					{capture name=loginLinks}<a href="#" id="loginOpener">{#orgAuthLogin#}</a> | <a href="{getUrl controller="sab_organizer_auth" action="register"}">{#register#}</a>{/capture}
					{else}
					{capture name=loginLinks}<a href="{getUrl controller="sab_organizer_self"}">{$session_user}</a> | <a href="#" onclick="location.href='{getUrl controller="sab_organizer_auth" action="logout"}'; return false;">{#orgAuthLogout#}</a>'
					{/capture}
				{/if}
				{ include file="coolbutton.tpl" content=$smarty.capture.loginLinks notlink=1 }
				
				{include file="common/langbar.tpl"}
			</div>
			{if !$session_user}
				{capture name=loginFormAction}
				{getUrl add=1 controller="sab_organizer_auth" action="login"}
				{/capture}
				{capture name=registerFormLink}
				{getUrl add=1 controller="sab_organizer_auth" action="register"}
				{/capture}
				{include file="common/loginPopup.tpl" formAction=$smarty.capture.loginFormAction registerURL=$smarty.capture.registerFormLink}
			{/if}

		</div>
	</div>
	<div class="main-menu bg">
		<div class="body floatfix">
			{include file="common/mainMenuOrganizers.tpl"}
		</div>
	</div>
	<div id="content">
		<div class="body floatfix">
			<div class="page-content">
				{if $selected_language=='ru'}
					{include file="controllers_frontend/`$user_params.controller`/`$user_params.action`.tpl"}
				{else}
					{include file="controllers_frontend/`$user_params.controller`/`$selected_language`/`$user_params.action`.tpl"}
				{/if}
			</div>
		</div>
	</div>
</div>

{include file="common/footer.tpl"}