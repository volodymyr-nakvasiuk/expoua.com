<h4>Просмотр сообщения компании</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <tr>
  <td width="120">Имя:</td>
  <td>{$entry.name|escape:"html"}</td>
 </tr>
 <tr>
  <td>Email:</td>
  <td>{$entry.email|escape:"html"}</td>
 </tr>
 <tr>
  <td>Телефон:</td>
  <td>{$entry.phone|escape:"html"}</td>
 </tr>
 <tr>
  <td>Компания:</td>
  <td>{$entry.company_name|escape:"html"}</td>
 </tr>
 <tr>
  <td colspan="2">Сообщение:<br/>
  {$entry.message|escape:"html"|nl2br}</td>
 </tr>

</TABLE>

<p><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></p>