{config_load file=global.conf}{config_load file=`$user_params.controller`.conf}<html>
<head>
  <title>Exhibition Global Marketing System</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="{$document_root}css/admin/expopromoter_style.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="{$document_root}css/admin/org_style.css" type="text/css" media="screen" />
  <script type="text/javascript" src="{$document_root}js/adminGeneral.js"></script>
  <script type="text/javascript" src="{$document_root}js/jquery.js"></script>

  <script>
  {literal}
    $(document).ready(function() {
      changeLanguage('{/literal}{$selected_language}{literal}');
    });

    function changeLanguage(code) {
      {/literal}{foreach from=$list_languages item="lang"}
      $("#data_top_{$lang.code}").css("display", "none");
      {/foreach}{literal}
      $("#data_top_" + code).css("display", "block");

      $("#langbar td").removeClass('active');
      $("#langbar #langtab-" + code).addClass('active');
    }
    {/literal}
  </script>
</head>

<body>

<table border="0" style="border-collapse:collapse;" cellpadding="5">
<tr>
  <td>{#exhibition#}:</td>
  <td>{$data.brand_name}</td>
</tr>

<tr>
  <td>{#date_pub#}:</td>
  <td>{$data.common.date_public}</td>
</tr>
</table>

{include file="common/editor-langbar.tpl"}

{foreach from=$list_languages item="lang"}
<table border="0" width="100%" style="border-collapse:collapse;" cellpadding="5" id="data_top_{$lang.code}">
<tr>
  <td>{#title#}:</td>
  <td>{$data[$lang.code].name}</td>
</tr>

<tr>
  <td colspan="2">
    {#teazer#}:<br />
    {$data[$lang.code].preambula|nl2br}
  </td>
</tr>

<tr>
  <td colspan="2">
    {#full_text#}:<br />
    {$data[$lang.code].content}
  </td>
</tr>
</table>
{/foreach}

<table border="0" width="100%" style="border-collapse:collapse;">
<tr>
  <td align="center">
    <input type="button" value=" {#closeAction#} " onClick="window.close();" />
  </td>
</tr>
</table>

</body>
</html>
