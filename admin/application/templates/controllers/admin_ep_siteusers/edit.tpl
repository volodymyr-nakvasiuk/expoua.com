<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT type="text/javascript" language="javascript">

objCountriesList = Shelby_Backend.ListHelper.cloneObject('objCountriesList');

objCountriesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'));
objCountriesList.returnFieldId = 'countries_id';
objCountriesList.feedUrl = '{getUrl controller="admin_ep_locations_countries" action="list" feed="json"}';
objCountriesList.writeForm();
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="update"}">

<h4>Редактируем пользователя сайта</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Логин:</TD>
  <TD>{$entry.login}</TD>
 </TR>
 <TR>
  <TD>Пароль:</TD>
  <TD><INPUT type="text" size="40" name="passwd" value="{$entry.passwd}"></TD>
 </TR>
 <TR>
  <TD>ФИО:</TD>
  <TD><INPUT type="text" size="40" name="text_fio" value="{$entry.text_fio}"></TD>
 </TR>
 <TR>
  <TD>Страна:</TD>
  <TD>
   <INPUT type="text" size="4" name="countries_id" id="countries_id" value="{$entry.countries_id}">
   <INPUT type="button" onclick="objCountriesList.showPopUp();" value="Выбрать">
   <SPAN id="countries_id_name">{$entry.countryName}</SPAN>
  </TD>
 </TR>
 <TR>
  <TD>Город:</TD>
  <TD><INPUT type="text" size="40" name="text_gorod" value="{$entry.text_gorod}"></TD>
 </TR>
 <TR>
  <TD>Компания:</TD>
  <TD><INPUT type="text" size="40" name="text_comp" value="{$entry.text_comp}"></TD>
 </TR>
 <TR>
  <TD>Должность:</TD>
  <TD><INPUT type="text" size="40" name="text_dolgnost" value="{$entry.text_dolgnost}"></TD>
 </TR>
 <TR>
  <TD>Телефон:</TD>
  <TD><INPUT type="text" size="40" name="text_tel" value="{$entry.text_tel}"></TD>
 </TR>
 <TR>
  <TD>Email:</TD>
  <TD><INPUT type="text" size="40" name="text_email" value="{$entry.text_email}"></TD>
 </TR>
 <TR>
  <TD>Сфера деятельности:</TD>
  <TD><INPUT type="text" size="40" name="text_sfera" value="{$entry.text_sfera}"></TD>
 </TR>
 <TR>
  <TD>Факс:</TD>
  <TD><INPUT type="text" size="40" name="text_fax" value="{$entry.text_fax}"></TD>
 </TR>
 <TR>
  <TD>URL:</TD>
  <TD><INPUT type="text" size="40" name="text_url" value="{$entry.text_url}"></TD>
 </TR>
 <TR>
  <TD>Как узнали:</TD>
  <TD><INPUT type="text" size="40" name="text_uznali" value="{$entry.text_uznali}"></TD>
 </TR>
 <TR>
  <TD colspan="2">
   Комментарии:<br>
   <textarea rows="3" cols="40">{$entry.textarea_comment}</textarea>
  </TD>
 </TR>

 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Обновить"></TD></TR>
</TABLE>

</FORM>