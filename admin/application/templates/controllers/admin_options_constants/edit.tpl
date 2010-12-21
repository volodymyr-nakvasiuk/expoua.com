<h4>Редактируем конфигурационную константу</h4>

 <FORM method="POST" action="{getUrl add="1" action="update"}">
 <TABLE>
  <TR><TD>Код: </TD><TD><INPUT type="text" name="code" value="{$entry.code}" size="20" /></TD></TR>
  <TR><TD>Значение: </TD><TD><INPUT type="text" name="value" value="{$entry.value}" size="20" /></TD></TR>
  <TR><TD>Описание: </TD><TD><INPUT type="text" name="description" value="{$entry.description}" size="50" /></TD></TR>
  </TABLE>

  <INPUT type="submit" value="Обновить" />

</FORM>
 <p><a href="{getUrl add="1" action="list"}">Вернуться к списку</a>