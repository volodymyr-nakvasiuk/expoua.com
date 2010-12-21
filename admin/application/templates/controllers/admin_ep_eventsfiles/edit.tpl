<FORM method="post" action="{getUrl add="1" action="update"}" enctype="multipart/form-data">

<h4>Редактируем прикрепленный файл</h4>

<TABLE border="1" style="border-collapse:collapse;">
 <TR>
  <TD class="wb">Название:</TD>
  <TD><INPUT type="text" size="50" name="name" value="{$entry.name}"></TD>
 </TR>
 <TR>
  <TD class="wb">Имя файла:</TD>
  <TD><INPUT type="text" size="50" name="filename" value="{$entry.filename}"></TD>
 </TR>
 <TR>
  <TD colspan="2" align="center"><INPUT type="submit" value="Изменить"></TD>
 </TR>
</TABLE>

<p><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></p>