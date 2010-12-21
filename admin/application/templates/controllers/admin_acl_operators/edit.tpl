<h4>Редактируем оператора</h4>

<FORM method="post" action="{getUrl add="1" action="update"}">
<TABLE border="1" style="border-collapse:collapse;" align="center">
 <TR>
  <TD>Login:</TD>
  <TD><INPUT type="text" name="login" value="{$entry.login}" /></TD>
 </TR>
 <TR>
  <TD>Пароль:</TD>
  <TD><INPUT type="text" name="passwd" value="{$entry.passwd}" /></TD>
 </TR>
 <TR>
  <TD>ФИО:</TD>
  <TD><INPUT type="text" name="name_fio" value="{$entry.name_fio}" size="50" /></TD>
 </TR>
 <TR>
  <TD>Телефон:</TD>
  <TD><INPUT type="text" name="phone" value="{$entry.phone}" size="50" /></TD>
 </TR>
 <TR>
  <TD>Email:</TD>
  <TD><INPUT type="text" name="email" value="{$entry.email}" size="50" /></TD>
 </TR>
</TABLE>

<p><CENTER><b>Доступные страны</b></CENTER></p>

{foreach from=$list_countries item="el"}
 <DIV style="float:left; width: 33%;"><INPUT type="checkbox" name="country[{$el.id}]" value="{$el.id}"{if isset($entry.countries[$el.id])} checked{/if} />{$el.name}</DIV>
{/foreach}

<DIV style="clear:both;"></DIV>

<BR>

<INPUT type="submit" value="Обновить">

</FORM>

<hr />

<form action="{getUrl controller='sab_operator_auth' action='login'}" method="post" target="_blank">
<input type="hidden" name="login" value="{$entry.login}" /><input type="hidden" name="passwd" value="{$entry.passwd}" /><input type="submit" value=" Вход " />
</form>

<hr />

<A href="{getUrl add="1" action="list"}">Вернуться к списку</A>