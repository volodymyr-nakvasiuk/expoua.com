<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>
objOrganizersList = Shelby_Backend.ListHelper.cloneObject('objOrganizersList');

objOrganizersList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('city_name', 'Город'));
objOrganizersList.returnFieldId = 'organizers_id';
objOrganizersList.feedUrl = '{getUrl controller="admin_ep_organizers" action="list" feed="json"}';
objOrganizersList.writeForm();
</SCRIPT>

<h4>Редактируем доступ организатора</h4>

<FORM method="post" action="{getUrl add="1" action="update"}">
<TABLE border="1" style="border-collapse:collapse;">
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
 <TR>
  <TD>Должность:</TD>
  <TD><INPUT type="text" name="position" value="{$entry.position}" size="50" /></TD>
 </TR>
 <TR>
  <TD>Организатор текст:</TD>
  <TD><INPUT type="text" name="organizer_name" value="{$entry.organizer_manual_name}" size="50" /></TD>
 </TR>
 <TR>
  <TD>Организатор:</TD>
  <TD><INPUT type="text" name="organizers_id" id="organizers_id" size="5" value="{$entry.organizers_id}" /> <INPUT type="button" onclick="objOrganizersList.showPopUp();" value="Выбрать"> <SPAN id="organizers_id_name">{$entry.organizer_name}</SPAN></TD>
 </TR>
 <TR>
  <TD>Язык:</TD>
  <TD>
<select name="user_languages_id">
 {foreach from=$list_languages item="el"}
  <option value="{$el.id}"{if $el.id == $entry.user_languages_id} selected="selected"{/if}>{$el.name}</option>
 {/foreach}
</select>
  </TD>
 </TR>
</TABLE>

<BR>

<INPUT type="submit" value="Обновить">

</FORM>

<hr />

<form action="http://event.expopromoter.com{getUrl controller='sab_organizer_auth' action='login'}" method="post" target="_blank">
<input type="hidden" name="login" value="{$entry.login}" /><input type="hidden" name="passwd" value="{$entry.passwd}" /><input type="submit" value=" Вход " />
</form>

<hr />

<A href="{getUrl add="1" action="list"}">Вернуться к списку</A>