<h4>Редактируем свойства галлереи</h4>

<FORM method="POST" action="{getUrl add="1" action="update"}">

<TABLE>
<TR><TD>Название: </TD><TD><INPUT type="text" name="name" value="{$entry.name}" size="50" /></TD></TR>
<TR><TD>Размер уменьшенных копий: </TD><TD><INPUT type="text" size="3" name="thumbnail_width" value="{$entry.thumbnail_width}">x<INPUT type="text" size="3" name="thumbnail_height" value="{$entry.thumbnail_height}"></TD></TR>
</TABLE>

<INPUT type="submit" value="Обновить" />

</FORM>

<A href="{getUrl add="1" action="list"}">Вернуться к списку</A>