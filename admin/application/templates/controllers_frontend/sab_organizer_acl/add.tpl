<h2>{#add_user#}</h2>

<p><A href="{getUrl add="1" action="list"}">{#returnToListUsers#}</A></p>


<FORM method="post" action="{getUrl add="1" action="insert"}">
<INPUT type="hidden" name="active" value="1" />

<TABLE border="0" width="100%" style="border-collapse:collapse;" cellpadding="5">
 <TR>
  <TD width="200" class="wb">{#login#}:</TD>
  <TD><INPUT type="text" name="login" size="25" /></TD>
 </TR>
 <TR>
  <TD width="200" class="wb">{#pswd#}:</TD>
  <TD><INPUT type="text" name="passwd" size="25" /></TD>
 </TR>
 <TR>
  <TD width="200" class="wb">{#name#}:</TD>
  <TD><INPUT type="text" name="name_fio" size="25" /></TD>
 </TR>
<tr>
  <td width="200" class="wb">{#phone#}:</td>
  <td><input type="text" name="phone" value="{$entry.phone}" size="25" /></td>
</tr>

<tr>
  <td width="200" class="wb">{#email#}:</td>
  <td><input type="text" name="email" value="{$entry.email}" size="50" /></td>
</tr>

<tr>
  <td width="200" class="wb">{#position#}:</td>
  <td><input type="text" name="position" value="{$entry.position}" size="50" /></td>
</tr>

<tr>
  <td width="200" class="wb">{#superUser#}:</td>
  <td><input type="checkbox" name="super" size="25" value="1" /></td>
</tr>
<tr>
  <td width="200" class="wb">{#modules#}:</td>
  <td>
    <input type="checkbox" name="acl_events_drafts" value="1" /> {#mngExhibitions#}<br />
    <input type="checkbox" name="acl_emails" value="1" /> {#addrQO#}<br />
    <input type="checkbox" name="acl_news" value="1" /> {#addRibNews#}
  </TD>
</TR>
<TR>
  <TD valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td class="wb">{#exhibitions#}:</td>
    </tr>
    </table>
    <BR /><BR />
  </TD>
  <TD>
  {foreach from=$list_brands item="el"}
  <INPUT type="checkbox" name="brands[{$el.id}]" value="{$el.id}" /> {$el.name}<BR />
  {/foreach}
  </TD>
</TR>
<TR><TD colspan="2" align="center"><INPUT type="submit" value=" {#addAction#} " /></TD></TR>
</TABLE>
</FORM>

<p><A href="{getUrl add="1" action="list"}">{#returnToListUsers#}</A></p>

