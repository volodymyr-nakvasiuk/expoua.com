{include file="common/contentVisualEdit.tpl" textarea="description"}

<h4>Редактируем свойства изображения галлереи</h4>

<FORM method="POST" action="{getUrl add="1" action="update"}">

<TABLE width="100%">
<TR><TD width="150">Название: </TD><TD><INPUT type="text" name="name" value="{$entry.name}" size="50" /></TD></TR>
<TR><TD>Альтернативный текст: </TD><TD><INPUT type="text" name="alt" value="{$entry.alt}" size="50" /></TD></TR>
<TR><TD colspan="2">
Описание:<br />
<TEXTAREA style="width:100%; height:250px;" name="description">{$entry.description|escape}</TEXTAREA>
</TD></TR>
</TABLE>

<INPUT type="submit" value="Обновить" />

</FORM>

<A href="{getUrl add="1" action="list"}">Вернуться к списку</A>