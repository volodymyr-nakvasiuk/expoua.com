<h4>Редактирование одиночного элемента</h4>

<FORM action="{getUrl add="1" action="update"}" method="post">
Название: <INPUT type="text" name="name" value="{$entry.name}"><BR />
<INPUT type="submit" value="Обновить">
</FORM>

<p><a href="{getUrl controller="admin_cms_objects_elements" action="list" parent=$entry.parent_id}">Вернуться к списку</a></p>