{config_load file=global.conf}<HTML>
<head>
  <title>Exhibition Global Marketing System</title>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8">
  {*<link rel="stylesheet" href="{$document_root}css/admin/expopromoter_style.css" type="text/css" media="screen" />*}
  <link rel="stylesheet" type="text/css" media="screen,projection" href="http://expopromoter.com/css/expopromoter_style.css" />
  <SCRIPT type="text/javascript" src="{$document_root}js/adminGeneral.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/adminAutocomplete.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/jquery.js"></SCRIPT>

  <script type="text/javascript">
  {literal}
  
  var allowSubmit = false;
  
  function checkUser(login) {
    var url = '{/literal}{getUrl controller="sab_jsonfeeds" action="checkcompanyuser"}{literal}id/' + login + "/";
  
    $.getJSON(url, function(json) {
      if (json.entry == 1) {
        $("#check_result").html('&#215;');
        $("#check_result").css("background-color", '#FF0000');
        allowSubmit = false;
        $("#submit_button").attr("disabled", "disabled");
      } else {
        $("#check_result").html('&#8730;');
        $("#check_result").css("background-color", '#00FF00');
        allowSubmit = true;
        $("#submit_button").removeAttr("disabled");
      }
    });
  }
  
  function checkFrom() {
    if (allowSubmit == false) {
      alert('{/literal}{#AuthUserNotExist#}{literal}');
      return false;
    }
  
    return true;
  }
  </script>
  {/literal}
</head>

{if $smarty.post.c}{assign var='redir_controller' value=$smarty.post.c}{else}{assign var='redir_controller' value='sab_partner_self'}{/if}
{if $smarty.post.a}{assign var='redir_action' value=$smarty.post.a}{else}{assign var='redir_action' value='edit'}{/if}

<body>

<div id="main">

<div id="wrapper" style="margin:0; padding:0;">

  <div id="header">
    <div id="logo"><a href="http://expopromoter.com/{$selected_language}/"><span>ExpoPromoter.com</span></a></div>

    {include file="common/langbar.tpl"}
    
    <div id="adstat">
      <h3>{#msgServcompAuth#}</h3>
    </div>
    

  </div>
  
  <div id="topmenu">
    <ul>
      <li class="first"><a href="http://expopromoter.com/{$selected_language}/" title=""><span>ExpoPromoter.com</span></a></li>
    </ul>
  </div>
  

  <div id="static-content" style="margin:0; padding:100px 0 0 0; text-align:center;">
    {if isset($last_action_result)}
      <p style="text-align:center;">
      {if $last_action_result==1}
        {if $user_params.action=='logout'}
          {#AuthLogOutSuc#}
        {else}
          {#AuthLogInSuc#}<br /><img src="/images/admin/loadingAnimation.gif" alt="Wait..." />
          
          <script type="text/javascript">
          setTimeout("redir()", 200);
          function redir() {ldelim}
            document.location.href="{getUrl controller=$redir_controller action=$redir_action}";
          {rdelim}
          </script>
        {/if}
      {elseif $last_action_result==-1}
        {#AuthUserNotExist#}
      {elseif $last_action_result==-3}
        {#AuthPswdIncorrect#}
      {/if}
      </p>
    {/if}
    
    <div id="filter" style="margin:0 auto; width:420px; text-align:left; border:1px solid #999;">
      <form id="loginForm" method="post" action="{getUrl add="1" action="login"}">
      <input type="hidden" name="c" value="sab_servcompany_index" />
      <input type="hidden" name="a" value="index" />
    
      <table align="center" cellspacing="0" border="0">
      <tr>
        <td>{#login#}:</td>
        <td><input type="text" size="20" id="_login" name="login" style="width:170px"></td>
      </tr>
      <tr>
        <td>{#pswd#}:</td>
        <td><input type="password" id="_password" name="passwd" style="width:170px;"></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><button type="submit"> OK </button></td>
      </tr>
      </table>
    
      </form>
    </div>
  </div>


  <div id="footer">
    <div id="copy" style="text-align:center;">
      {#mainCopyright#} {#mainContacts#}
    </div>
  </div>
</div>

</body>
</html>
