{config_load file=global.conf}{config_load file=`$user_params.controller`.conf}
{*fetch file="`$host_site`Include/lang/`$selected_language`/section/header/"*}
{if $user_params.site}{include file="includes/`$user_params.site`/`$selected_language`/header.tpl"}{else}{include file="includes/expotop_ru/`$selected_language`/header.tpl"}{/if}
{*
<HTML>
<HEAD>
  <TITLE>Exhibition Global Marketing System</TITLE>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <LINK rel="stylesheet" href="{$document_root}css/admin/admin_style.css" type="text/css" media="screen" />
  <LINK rel="stylesheet" href="{$document_root}css/admin/org_style.css" type="text/css" media="screen" />
  <LINK rel="stylesheet" href="{$document_root}css/admin/jqModal.css" type="text/css" media="screen" />
  <LINK rel="stylesheet" href="{$document_root}css/admin/ui.datepicker.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="{$document_root}css/admin/jquery.cluetip.css" type="text/css" />
  <SCRIPT type="text/javascript" src="{$document_root}js/adminGeneral.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/jquery.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/jqExtensions/jqModal.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/jqExtensions/ui.datepicker.js"></SCRIPT>
</HEAD>

<BODY style="padding:0 10px 10px 10px; margin:0px">
*}

<!--main Content1-->
<table id="main1" cellpadding="0" cellspacing="0" border="0">
<tr valign="top">
  <td>
    {if $user_params.controller == 'Mainpage' or $user_params.controller == 'Exhibition'}{include file="filter.php"}{/if}

    <!--Main-->
    <table id="main" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr valign="top">
      <!--Content-->
      <td id="content">
        <h2>{#hdrOnlineExpo#}</h2>

        <div id="cms-wrapper">
          <table width="560" align="center">
          <tr valign="middle">
            <td style="height:30px; text-align:center; padding:5px 10px; background:#FFF4CC; font-size:105%">
              <table width="100%" style="padding-bottom:5px;">
              <tr>
                <td width="35%">
                  {#captionCompany#} <b>{$company_data.name}</b><br />
                  {#captionYourLogin#} <b>{$session_user}</b><br />
                  <span class="blue_hilight"><a href="{$host_site}User/lang/{$selected_language}/">{#orgAuthLogout#}</a></span>
                </td>
                <td style="border-left: 1px solid #CCCCCC;">
                  <img src="/images/admin/info.gif" align="left" hspace="8">
                  {#infoCompanyAttention#}
                  <b>
                  <a href="http://www.expoua.com/{if $company_data.active==1}Companies/lang/{$selected_language}/id/{$session_user_companies_id}/{/if}" target="_blank" style="color:blue;">www.ExpoUA.com</a>,
                  <a href="http://www.expotop.ru/{if $company_data.active==1}Companies/lang/{$selected_language}/id/{$session_user_companies_id}/{/if}" target="_blank" style="color:blue;">www.ExpoTop.ru</a>,
                  <A href="http://www.expotop.com/" target="_blank" style="color:blue;">www.ExpoTop.com</a>
                  </b>
                </td>
              </tr>
              </table>
            </td>
          </tr>
          </table>


          {include file="controllers_frontend/`$user_params.controller`/`$user_params.action`.tpl"}
        </div>
      </td>
      <!--end Content-->
    </tr>
    </table>
    <!--end Main-->

  </td>

  <td id="sidebar" class="sidebar">
    <div class="list1">
      <h4>{#umenuHeaderFavorites#}</h4>

      <ul>
        <li><a href="{$host_site}User/lang/{$selected_language}/">{#umenuSummary#}</a></li>

        <li><a href="{$host_site}User/lang/{$selected_language}/action/schedule/mode/MyEvents/">{#umenuEvents#}</a></li>
        <li><a href="{$host_site}User/lang/{$selected_language}/action/schedule/mode/MyCompanies/">{#umenuCompanies#}</a></li>
        <li><a href="{$host_site}User/lang/{$selected_language}/action/schedule/mode/MyCompanyServices/">{#umenuCompanyServices#}</a></li>
        <li><a href="{$host_site}User/lang/{$selected_language}/action/schedule/mode/MyVenues/">{#umenuVenues#}</a></li>
      </ul>
    </div>


    <div class="list1">
      <h4>{#umenuHeaderCompany#}</h4>

      <ul>
        <li><a href="{getUrl controller="sab_company_self" action="edit"}">{if $user_params.controller=='sab_company_self'}<strong>{#umenuCompanyProfile#}</strong>{else}{#umenuCompanyProfile#}{/if}</a></li>
        <li><a href="{getUrl controller="sab_company_services" action="list"}">{if $user_params.controller=='sab_company_services'}<strong>{#umenuCompanyProductsServices#}</strong>{else}{#umenuCompanyProductsServices#}{/if}</a></li>
        <li><a href="{getUrl controller="sab_company_news" action="list"}">{if $user_params.controller=='sab_company_news'}<strong>{#umenuCompanyPressReleases#}</strong>{else}{#umenuCompanyPressReleases#}{/if}</a></li>
        <li><a href="{getUrl controller="sab_company_eventspart" action="edit"}">{if $user_params.controller=='sab_company_eventspart'}<strong>{#umenuCompanyExhibitions#}</strong>{else}{#umenuCompanyExhibitions#}{/if}</a></li>
        <li><a href="{getUrl controller="sab_company_employers" action="list"}">{if $user_params.controller=='sab_company_employers'}<strong>{#umenuCompanyEmployees#}</strong>{else}{#umenuCompanyEmployees#}{/if}</a></li>
        <li><a href="{getUrl controller="sab_company_help" action="index"}">{if $user_params.controller=='sab_company_help'}<strong>{#hdrCompanyHelp#}</strong>{else}{#hdrCompanyHelp#}{/if}</a></li>
      </ul>
    </div>

    <div class="list1">
      <h4>{#umenuHeaderProfile#}</h4>

      <ul>
        <li><a href="{$host_site}User/lang/{$selected_language}/action/edit/">{if $action=='edit'}<strong>{#umenuEditProfile#}</strong>{else}{#umenuEditProfile#}{/if}</a></li>
        <li><a href="{$host_site}User/lang/{$selected_language}/action/settings/">{if $action=='settings'}<strong>{#umenuSettings#}</strong>{else}{#umenuSettings#}{/if}</a></li>
      </ul>
    </div>
  </td>
</tr>
</table>



{if $user_params.site}{include file="includes/`$user_params.site`/`$selected_language`/footer.tpl"}{else}{include file="includes/expotop_ru/`$selected_language`/footer.tpl"}{/if}

<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-978984-1";
urchinTracker();
</script>

{if $smarty.get.debug}{debug}{/if}

</BODY>
</HTML>