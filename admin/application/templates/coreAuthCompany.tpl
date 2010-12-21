{config_load file=global.conf}{config_load file=`$user_params.controller`.conf}<HTML>
<HEAD>
  <TITLE>Exhibition Global Marketing System</TITLE>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8">
  <LINK rel="stylesheet" href="{$document_root}css/admin/admin_style.css" type="text/css">
  <LINK rel="stylesheet" href="{$document_root}css/admin/org_style.css" type="text/css" media="screen" />
  <SCRIPT type="text/javascript" src="{$document_root}js/adminGeneral.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/adminAutocomplete.js"></SCRIPT>
  <SCRIPT type="text/javascript" src="{$document_root}js/jquery.js"></SCRIPT>

{literal}
<STYLE type="text/css">
HTML, BODY {
  height: 98%;
}
</STYLE>

<SCRIPT type="text/javascript">

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
<body id="cms-wrapper">

<div style="text-align:center">
  <h1 align="center" style="padding-top:5px;">{#hdrOnlineExpo#}</h1>

  <div style="margin:100px auto 0 auto; padding:10px; width:400px; border:1px solid #999;">
  {if isset($last_action_result)}
    {if $last_action_result==1}
    {if $user_params.action=='logout'}
      {#AuthLogOutSuc#}
    {else}
      {#AuthLogInSuc#}
      <br>
      <br><img src="/images/admin/loadingAnimation.gif" alt="Wait..." />
      <script type="text/javascript">
      setTimeout("redir()", 200);
      function redir() {ldelim}
          document.location.href="{getUrl controller=$smarty.post.c action=$smarty.post.a site=$smarty.post.s}";
      {rdelim}
      </script>
    {/if}
    {elseif $last_action_result==-1}
    {#AuthUserNotExist#}
    {elseif $last_action_result==-3}
    {#AuthPswdIncorrect#}
    {/if}
  {/if}

  <form id="loginForm" method="post" action="{getUrl add="1" action="login"}" style="margin:40px 0 0 0;">
  <input type="hidden" name="c" value="sab_company_self" />
  <input type="hidden" name="a" value="edit" />
  <input type="hidden" name="s" value="{$user_params.site}" />

  <table align="center" cellspacing="0" border="0">
  <tr>
    <td>{#login#}: </td><td><input type="text" size="20" id="_login" name="login" style="width:200px"></td>
  </tr>
  <tr>
    <td>{#pswd#}: </td><td><input type="password" id="_password" name="passwd" style="width:200px"></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" value=" OK "></td>
  </tr>
  </table>

  </form>

  </div>
</div>

</body>
</html>