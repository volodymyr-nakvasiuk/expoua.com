<h4>Редактируем пользователя админки</h4>

<FORM action="{getUrl add="1" action="update"}" method="POST">

<TABLE border="1" style="border-collapse:collapse;" cellpadding="5">

<TR><TD valign="top">

 Login: <INPUT type="text" name="login" value="{$entry.login}"><br />
 Имя: <INPUT type="text" name="name" value="{$entry.name}"><br />
 Дата добавления: {$entry.time_added}<br />
 Дата последнего входа: {$entry.time_lastlogin}<br /><br /><br />
 <center><INPUT type="submit" value="Изменить" /></center>

</TD><TD style="padding-left:10px;" valign="top">

<b>Группы пользователя:</b><br />
{foreach from=$list_groups item="el"}
 <INPUT type="checkbox" name="groups_ids[{$el.id}]" value="{$el.id}"{if !empty($entry.groups[$el.id])} checked{/if}> <LABEL>{$el.name}</LABEL><br />
{/foreach}

</TD></TR>
</TABLE>
</FORM>

<b>Изменить пароль</b>
<FORM action="{getUrl add="1" action="update"}" method="POST">
<TABLE border="1" style="border-collapse:collapse;">
 <TR>
  <TD>Новый пароль: <INPUT type="text" size="20" name="passwd"></TD>
 </TR>
 <TR>
  <TD><INPUT type="submit" value="Изменить"></TD>
 </TR>
</TABLE>
</FORM>

<A href="{getUrl add="1" action="list"}">Вернуться к списку</A>