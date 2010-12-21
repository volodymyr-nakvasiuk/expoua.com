{config_load file=global.conf}{config_load file=`$user_params.controller`.conf}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>{if $user_params.controller=="sab_organizer_brandsevents" && $user_params.action=="edit"}{#exhEdit#} {$entry[$selected_language].brand_name|escape:html}{else}Exhibition Global Marketing System{/if}</title>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  {*<link rel="stylesheet" href="{$document_root}css/admin/admin_style.css" type="text/css" media="screen" />*}
  {* <link rel="stylesheet" href="{$document_root}css/admin/org_style.css" type="text/css" media="screen" /> *}
  <link rel="stylesheet" href="{$document_root}css/admin/expopromoter_style.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="{$document_root}css/admin/jqModal.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="{$document_root}css/admin/ui.datepicker.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="{$document_root}css/admin/jquery.cluetip.css" type="text/css" />
  <script type="text/javascript" src="{$document_root}js/adminGeneral.js"></script>
  <script type="text/javascript" src="{$document_root}js/jquery.js"></script>
  <script type="text/javascript" src="{$document_root}js/jqExtensions/jqModal.js"></script>
  <script type="text/javascript" src="{$document_root}js/jqExtensions/ui.datepicker.js"></script>

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
  {/literal}
  </script>

</head>

<body>

<div id="wrapper0">

  <div id="header">
    <div id="logo">&nbsp;</div>

    {include file="common/langbar.tpl"}

    <div id="adstat">
      <big>
        {#msgServcompAreaTitle#}<br />
        <strong>{$organizer.name}</strong>
      </big>
    </div>

   <div id="auth">
    {if $session_user}
      {#captionYourLogin#}<br />
      <strong>{$session_user}</strong><br />
      <button type="button" onclick="location.href='{getUrl controller="sab_servcompany_auth" action="logout"}'; return false;"> {#orgAuthLogout#} </button>
    {/if}
    </div>
  </div>
</div>

<div id="wrapper">
  <div id="menu">
    <ul>
      <li class="{if $user_params.controller == 'sab_servcompany_index'}current{/if} first">
        <a href="{getUrl controller="sab_servcompany_index"}">{#mnuOrganizerSystemNews#}</a>
      </li>

      <li class="{if $user_params.controller == 'sab_servcompany_self'}current{/if}">
        <a href="{getUrl controller='sab_servcompany_self' action='edit'}">{#mnuServcompCompanyInfo#}</a>
      </li>

      <li class="{if $user_params.controller == 'sab_servcompany_gallery'}current{/if} last">
        <a href="{getUrl controller="sab_servcompany_gallery" action="list"}">{#mnuServcompGallery#}</a>
      </li>
    </ul>
  </div>

  <div id="content">
    <h1>{#hdrPageTitle#}</h1>

    {if #msgPageDescription#}<p class="descr">{#msgPageDescription#}</p>{/if}

    {include file="controllers_frontend/`$user_params.controller`/`$user_params.action`.tpl"}
  </div>


  <div id="footer"></div>

</div>

{if $smarty.get.debug}{debug}{/if}

<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-978984-1";
urchinTracker();
</script>

</body>
</html>
