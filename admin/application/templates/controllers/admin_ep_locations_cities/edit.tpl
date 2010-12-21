<h4>Редактируем город</h4>

<form method="post" action="{getUrl add=1 action="update"}">
<table>
 <tr>
  <td>Название: </td>
  <td><input type="text" name="name" value="{$entry.name|escape:"html"}" size="30"/></td>
 </tr>
</table>
<input type="submit" value="Обновить"/>
</form>