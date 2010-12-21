<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>
objServicecompList = Shelby_Backend.ListHelper.cloneObject('objServicecompList');

objServicecompList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('city_name', 'Город'));
objServicecompList.returnFieldId = 'service_companies_id';
objServicecompList.feedUrl = '{getUrl controller="admin_ep_servicecomp" action="list" feed="json"}';
objServicecompList.writeForm();
</SCRIPT>

<h4>Редактируем доступ сервисной компании</h4>

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
  <TD>Сервисная компания:</TD>
  <TD><INPUT type="text" name="service_companies_id" id="service_companies_id" size="5" value="{$entry.service_companies_id}" /> <INPUT type="button" onclick="objServicecompList.showPopUp();" value="Выбрать"> <SPAN id="service_companies_id_name">{$entry.servicecomp_name}</SPAN></TD>
 </TR>
</TABLE>

<BR>

<CENTER><INPUT type="submit" value="Обновить"></CENTER>

</FORM>

<A href="{getUrl add="1" action="list"}">Вернуться к списку</A>