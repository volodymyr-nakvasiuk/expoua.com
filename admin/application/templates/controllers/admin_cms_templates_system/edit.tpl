<H4>Редактируем системный шаблон</H4>

{if is_array($entry)}
 <FORM method="POST" action="{getUrl add="1" action="update"}">
  Название: <INPUT type="text" name="name" size="20" value="{$entry.name}"><BR />
  Описание:<BR />
   <TEXTAREA name="description" cols="60" rows="3">{$entry.description|escape}</TEXTAREA><BR />
   <P>
   <TEXTAREA name="content" style="width:100%; height:400px;">{$entry.content|escape}</TEXTAREA>
   </P>
 <INPUT type="submit" value="Обновить">
 </FORM>
{else}
 Запись не существует
{/if}

<p><a href="{getUrl add="1" action="list"}">Вернуться к списку</a>