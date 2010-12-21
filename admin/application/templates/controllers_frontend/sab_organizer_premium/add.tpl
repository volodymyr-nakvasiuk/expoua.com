<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Exhibition Global Marketing System</title>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <link rel="stylesheet" href="{$document_root}css/admin/expopromoter_style.css" type="text/css" media="screen" />
</head>

<body>

<div id="wrapper9" style="padding:10px;">
  {assign var="page" value=$HCms->getEntry(66)}
  {$page.content}

  {if !empty($entry_event)}
  <h2>{#hdrPageTitle#} "{$entry_event.brand_name}"</h2>

  <form method="post" action="{getUrl add=1 action="insert"}">
  <table align="center">
  <tr>
    <td>{#captionEmail#}: </td>
    <td><input type="text" name="email" value="{$user_session_email}" size="25"/></td>
  </tr>
  <tr>
    <td colspan="2">
      {#captionComment#}:<br />
      <textarea name="message" style="height:60px; width:400px;"></textarea><br/>
      <br/>
      <input type="submit" value=" {#sendAction#} "/>
  </td></tr>
  </table>
  </form>
  {/if}
</div>

</body>
</html>
