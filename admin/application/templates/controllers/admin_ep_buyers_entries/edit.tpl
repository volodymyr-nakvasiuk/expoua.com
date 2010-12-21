<h4>Редактируем запись</h4>

<FORM method="post" action="{getUrl add="1" action="update"}">
<TABLE border="0" style="border-collapse:collapse;" width="400">
 <TR>
  <TD colspan="2"><b>Запрос баерской программы</b></TD>
 </TR>
 <TR>
  <TD width="175">Ставка:</TD>
  <TD>{$entry.money_request}</TD>
 </TR>
 <TR>
  <TD>Общее количество баеров:</TD>
  <TD>{$entry.buyers_request}</TD>
 </TR>
 <TR>
  <TD>Общий бюджет:</TD>
  <TD>{$entry.max_money_request}</TD>
 </TR>
 <TR>
  <TD>Страна:</TD>
  <TD>{$entry.geography}</TD>
 </TR>
 <TR>
  <TD>Контактное лицо:</TD>
  <TD>{$entry.contact_name}</TD>
 </TR>
 <TR>
  <TD>Контактный телефон:</TD>
  <TD>{$entry.contact_phone}</TD>
 </TR>
 <TR>
  <TD>Контактный email:</TD>
  <TD>{$entry.contact_email}</TD>
 </TR>
 <TR>
  <TD>Ставка:</TD>
  <TD><INPUT type="text" size="10" name="money" value="{$entry.money}"> (Формат ###.##)</TD>
 </TR>
 <TR>
  <TD>Общее количество баеров:</TD>
  <TD><INPUT type="text" size="10" name="buyers_required" value="{$entry.buyers_required}"></TD>
 </TR>
 <TR>
  <TD>Общий бюджет:</TD>
  <TD><INPUT type="text" size="10" name="max_money" value="{$entry.max_money}"></TD>
 </TR>

 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Обновить"></TD></TR>
</TABLE>
</FORM>

<br/><br/>

<FORM method="post" action="{getUrl add="1" action="insert" type="transactions"}">
<TABLE border="0" style="border-collapse:collapse;" width="400">
 <TR>
  <TD width="175">Внести на депозит:</TD>
  <TD><INPUT type="text" size="6" name="summ"> (Формат ###.##)</TD>
 </TR>

 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Добавить"></TD></TR>
</TABLE>
</FORM>

<a href="{getUrl add=1 del="id" action="list"}">Вернуться к списку</a>