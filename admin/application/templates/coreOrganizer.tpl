{config_load file=global.conf}{config_load file=`$user_params.controller`.conf}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>{if $user_params.controller=="sab_organizer_brandsevents" && $user_params.action=="edit"}{#exhEdit#} {$entry[$selected_language].brand_name|escape:html}{else}Exhibition Global Marketing System{/if}</title>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="{$document_root}css/admin/jqModal.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="{$document_root}css/admin/ui.datepicker.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="{$document_root}css/admin/jquery.cluetip.css" type="text/css" />
  <link rel="stylesheet" href="{$document_root}css/admin/layout.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="{$document_root}css/admin/colors.css" type="text/css" media="screen" />
  <script type="text/javascript" src="{$document_root}js/adminGeneral.js"></script>
  <script type="text/javascript" src="{$document_root}js/jquery.js"></script>
  <script type="text/javascript" src="{$document_root}js/jqExtensions/jqModal.js"></script>
  <script type="text/javascript" src="{$document_root}js/jqExtensions/jquery.dimensions.js"></script>
  <script type="text/javascript" src="{$document_root}js/jqExtensions/jquery.cluetip.js"></script>
  <script type="text/javascript" src="{$document_root}js/jqExtensions/ui.datepicker.js"></script>
  <script type="text/javascript" src="{$document_root}js/jqExtensions/jquery.colorpicker.js"></script>

  <script language="javascript" type="text/javascript">
  {literal}
    var win = null;

    function NewWindow(mypage, myname, w, h, scroll, pos){
      if (pos == "random") {
        LeftPosition = (screen.availWidth)  ? Math.floor(Math.random() * (screen.availWidth - w)) : 50;
        TopPosition  = (screen.availHeight) ? Math.floor(Math.random() * ((screen.availHeight - h) - 75)) : 50;
      }

      if (pos == "center") {
        LeftPosition = (screen.availWidth)  ? (screen.availWidth - w) / 2 : 50;
        TopPosition   = (screen.availHeight) ? (screen.availHeight - h) / 2 : 50;
      }

      if (pos == "default") {
        LeftPosition = 50;
        TopPosition = 50
      } else if ((pos != "center" && pos != "random" && pos != "default") || pos == null) {
        LeftPosition = 0;
        TopPosition = 20
      }

      settings =
        'width=' + w + ',height=' + h + ',top=' + TopPosition + ',left=' + LeftPosition + ',scrollbars=' + scroll + ',location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes';
      win = window.open(mypage, myname,settings);

      if (win.focus) {
        win.focus();
      }
    }

    $(function() {
      $('a.tips').cluetip({showTitle: false});

      {/literal}
      {if $selected_language != 'ru'}
        $.datepicker.setDefaults($.datepicker.regional['']);
        $.datepicker.setDefaults({ldelim} dateFormat: 'yy-mm-dd' {rdelim});
      {/if}
      {literal}
    });
  {/literal}
  </script>

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
					{capture name=loginLinks}<a href="{getUrl controller="sab_organizer_self"}">{$session_user}</a> | <a href="#" onclick="location.href='{getUrl controller="sab_organizer_auth" action="logout"}'; return false;">{#orgAuthLogout#}</a>
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
	{if $session_user}
	<div class="main-menu main-menu-empty bg" style="margin:0; "><!-- --></div>
	{else}
	<div class="main-menu bg" style="margin:0;">
		<div class="body">
		{include file="common/mainMenuOrganizers.tpl"}
		</div>
	</div>
	{/if}
	<div id="content" class="floatfix">
	  <div class="body floatfix">
		<div class="page-head floatfix">
			  <table cellspacing="0" class="table-menu">
			  <tr valign="top">
				<td>
				  <table cellspacing="0">
				  <tr valign="top">
				  
					<td>
					  <div class="item">{#mnuOrganizerCompany#}</div>
					  <ul>
						<li class="{if $user_params.controller == 'sab_organizer_self'}current{/if}">
						  <a href="{getUrl controller="sab_organizer_self" action="index"}">{#mnuOrganizerCompanyInfo#}</a>
						</li>

						{if isset($session_user_allow.sab_organizer_acl)}
						<li class="{if $user_params.controller == 'sab_organizer_acl' && ($user_params.action == 'list' || $user_params.action == 'edit')}current{/if}">
						  <a href="{getUrl controller="sab_organizer_acl" action="list"}">{#mnuOrganizerUserList#}</a>
						</li>

						<li class="{if $user_params.controller == 'sab_organizer_acl' && $user_params.action == 'add'}current{/if}">
						  <a href="{getUrl controller="sab_organizer_acl" action="add"}">{#mnuOrganizerAddUser#}</a>
						</li>
						{/if}

						<li class="{if $user_params.controller == 'sab_organizer_reqemails'}current{/if}">
						  <a href="{getUrl controller="sab_organizer_reqemails" action="list"}">{#mnuOrganizerQueryRecipients#}</a>
						</li>
						
						{if isset($session_user_allow.sab_organizer_news)}
						<li class="{if $user_params.controller == 'sab_organizer_news' && $user_params.action == 'list'}current{/if}">
						  <a href="{getUrl controller="sab_organizer_news" action="list"}">{#mnuOrganizerNewsList#}</a>
						</li>

						<li class="{if $user_params.controller == 'sab_organizer_news' && $user_params.action == 'add'}current{/if}">
						  <a href="{getUrl controller="sab_organizer_news" action="add"}">{#mnuOrganizerAddNews#}</a>
						</li>
						{/if}

					  </ul>
					</td>
					
					<td>
					  <div class="item first">{#mnuOrganizerYourEvents#}</div>
					  <ul>
						{if isset($session_user_allow.sab_organizer_brandsevents)}
						<li class="{if $user_params.controller == 'sab_organizer_brandsevents' || $user_params.controller == 'sab_organizer_eventsfiles' || $user_params.controller == 'sab_organizer_eventslogo'}current{/if}">
						  <a href="{getUrl controller="sab_organizer_brandsevents" action="list"}">{#mnuOrganizerEventsList#}</a>
						</li>
						{/if}

						{if isset($session_user_allow.sab_organizer_drafts)}
						<li class="{if $user_params.controller == 'sab_organizer_drafts'}current{/if}">
						  <a href="{getUrl controller="sab_organizer_drafts" action="add"}">{#mnuOrganizerAddEvent#}</a>
						</li>
						{/if}

						<li class="{if $user_params.controller == 'sab_organizer_gallery'}current{/if}">
						  <a href="{getUrl controller="sab_organizer_gallery" action="index"}">{#mnuOrganizerGallery#}</a>
						</li>

						{if isset($session_user_allow.sab_organizer_requests)}
						<li class="{if $user_params.controller == 'sab_organizer_requests'}current{/if}">
						  <a href="{getUrl controller="sab_organizer_requests" action="list"}">{#mnuOrganizerQueriesList#}</a>
						</li>
						{/if}

						<li class="{if $user_params.controller == 'sab_organizer_stat'}current{/if}">
						  <a class="td-none" href="{getUrl controller="sab_organizer_stat" action="list"}"><u>{#mnuOrganizerStatistics#}</u></a>
						</li>

						{if $selected_language == "ru"}
						<li class="{if $user_params.controller == 'sab_organizer_freetickets'}current{/if}">
						  <a class="td-none" href="{getUrl controller="sab_organizer_freetickets" action="index"}"><u>{#mnuOrganizerFreeTickets#}</u><sup>NEW</sup></a>
						</li>
						{/if}
					  </ul>
					</td>

                    <td>
                      <div class="item last">{#mnuOrganizerBookingSection#}</div>
                      <ul>
                        <li class="{if $user_params.controller == 'sab_organizer_info' && $user_params.id == 130}current{/if}">
                          <a class="td-none" href="{getUrl controller="sab_organizer_info" action="view" id="130"}"><u>{#mnuOrganizerServiceOverview#}</u></a>
                        </li>
                      {if isset($session_user_allow.sab_organizer_bookingwidget)}
                        <li class="{if $user_params.controller == 'sab_organizer_bookingwidget'}current{/if}">
                          <a class="td-none" style="font-weight: bold; font-size: 13px;" href="{getUrl controller="sab_organizer_bookingwidget" action="view"}"><u>{#mnuOrganizerBookingWidgets#}</u><sup>NEW</sup></a>
                        </li>
                      {/if}
                      {if isset($session_user_allow.sab_organizer_bookingstat)}
                        <li class="{if $user_params.controller == 'sab_organizer_bookingstat'}current{/if}">
                          <a class="td-none" href="{getUrl controller="sab_organizer_bookingstat" action="list"}"><u>{#mnuOrganizerBookingStats#}</u><sup>NEW</sup></a>
                        </li>
                      {/if}
                      </ul>
                    </td>

					<td>
					  <div class="item last">{#mnuOrganizerTicketsSection#}</div>
					  <ul>
					    <li class="{if $user_params.controller == 'sab_organizer_info' && $user_params.id == 131}current{/if}">
                          <a class="td-none" href="{getUrl controller="sab_organizer_info" action="view" id="131"}"><u>{#mnuOrganizerServiceOverview#}</u></a>
                        </li>
                        <li class="{if $user_params.controller == 'sab_organizer_tickets'}current{/if}">
                          <a class="td-none" style="font-weight: bold; font-size: 13px;" href="{getUrl controller="sab_organizer_tickets" action="view"}"><u>{#mnuOrganizerTickets#}</u><sup>NEW</sup></a>
                        </li>
						<li class="{if $user_params.controller == 'sab_organizer_ticketsmanage'}current{/if}">
						  <a class="td-none" href="{getUrl controller="sab_organizer_ticketsmanage" action="list"}"><u>{#mnuOrganizerTicketsManage#}</u><sup>NEW</sup></a>
						</li>
						<li class="{if $user_params.controller == 'sab_organizer_ticketsstat'}current{/if}">
						  <a class="td-none" href="{getUrl controller="sab_organizer_ticketsstat" action="list"}"><u>{#mnuOrganizerTicketsStats#}</u><sup>NEW</sup></a>
						</li>
					  </ul>
					</td>
					
					<td>
					  <div class="item last">Advert.ExpoPromoter</div>
					  <ul>
					    <li>
                          <a class="td-none" target="_blank" href="http://advertise.expopromoter.com/{$selected_language}/"><u>{#mnuOrganizerServiceOverview#}</u></a>
                        </li>
                        <li>
                          <a class="td-none" target="_blank" href="http://advertise.expopromoter.com/{$selected_language}/sab_banners_auth/register/"><u>{#register#}</u></a>
                        </li>
						<li>
						  <a class="td-none" target="_blank" href="http://advertise.expopromoter.com/{$selected_language}/sab_banners_auth/login/"><u>{#orgAuthEntrance#}</u></a>
						</li>
					  </ul>
					</td>
{*
					<td>
					  <div class="item">{#mnuOrganizerQA#}</div>

					  <ul>
						<li class="{if $user_params.controller == 'sab_organizer_index'}current{/if}">
						  <a href="{getUrl controller="sab_organizer_index" action="index"}">{#mnuOrganizerEpNews#}</a>
						</li>

						<li class="{if $user_params.controller == 'sab_organizer_messages'}current{/if}">
						  <a href="{getUrl controller="sab_organizer_messages" action="list"}">{#mnuOrganizerAsk#}</a>
						</li>

						<li class="{if $user_params.controller == 'sab_organizer_testimonials'}current{/if}">
						  <a class="td-none" href="{getUrl controller="sab_organizer_testimonials" action="index"}"><u>{#mnuOrganizerTestimonial#}</u></a>
						</li>
					  </ul>
					</td>
*}
				  </tr>
				  </table>
				</td>
			  </tr>
			  </table>
			  
		<div class="page-content floatfix" style="margin-right:0;">

			<h1 class="h-main h-first">{#hdrPageTitle#}</h1>

			{if #msgPageDescription#}<p class="descr">{#msgPageDescription#}</p>{/if}

			{include file="controllers_frontend/`$user_params.controller`/`$user_params.action`.tpl"}

		</div>
		<div class="page-column">
		    <div id="adstat" class="box box-compact">
				<h4 class="h">{#msgOrganizerAreaTitle#}</h4>
				<div class="content" style="padding:10px;">
				{$organizer.name}
				</div>
			</div>
		</div>
	  </div>
	</div>
  </div>
</div>

{include file="common/footer.tpl"}