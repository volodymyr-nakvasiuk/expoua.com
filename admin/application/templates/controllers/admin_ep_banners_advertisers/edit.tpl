<h4>Редактируем рекламодателя</h4>

<FORM method="post" action="{getUrl add="1" action="update"}">
<TABLE width="100%">
 <TR>
  <TD>Название: </TD>
  <TD><INPUT type="text" name="name" value="{$entry.name|escape:"html"}" size="80"/></TD>
 </TR>
 <TR>
  <TD>Описание: </TD>
  <TD><TEXTAREA name="description" style="width:90%; height:150px;">{$entry.description|escape:"html"}</TEXTAREA></TD>
 </TR>

 <TR><TD colspan="2" align="center"><INPUT type="submit" value="Изменить" /></TD></TR>
</TABLE>
</FORM>