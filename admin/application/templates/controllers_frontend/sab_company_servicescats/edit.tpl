<FORM method="post" action="{getUrl add="1" action="insert"}" enctype="multipart/form-data">

<h4>Редактируем категорию товаров или услуг</h4>

<table width="140" border="0" cellspacing="3" cellpadding="3">
  <tr class="yellow_hilight">
    <td><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></td>
  </tr>
  <tr class="yellow_hilight">
    <td>&nbsp;</td>
  </tr>
</table>

<FORM method="post" action="{getUrl add="1" action="update"}">

<TABLE border="0" width="100%" style="border-collapse:collapse;">
 {foreach from=$list_user_languages item="lang"}
 <TR>
  <TD class="wb">Название ({$lang.name}):</TD>
  <TD><INPUT type="text" size="70" name="{$lang.code}[name]" value="{$entry[$lang.code].name}"></TD>
 </TR>
 {/foreach}
 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Сохранить изменения"></TD></TR>
</TABLE>

</FORM>