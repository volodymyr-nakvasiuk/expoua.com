<h4>Переименовываем файл/каталог</h4>

<FORM method="post" action="{getUrl add="1" action="update"}">
<INPUT type="hidden" name="type" value="{$entry.type}">

Тип: {$entry.type}<br />
Размер: {$entry.size|number_format} байт<br />
Защищен от записи: {if $entry.writeable}нет{else}да{/if}<br />
Имя: <INPUT type="text" name="name" value="{$entry.name}" size="20"><br />

<p>
<INPUT type="submit" value="Переименовать">
</p>

</FORM>