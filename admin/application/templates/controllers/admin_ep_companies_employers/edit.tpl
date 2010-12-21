<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>

objCompaniesList = Shelby_Backend.ListHelper.cloneObject('objCompaniesList');

objCompaniesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'));
objCompaniesList.returnFieldId = 'companies_id';
objCompaniesList.feedUrl = '{getUrl controller="admin_ep_companies_manage" action="list" feed="json"}';
objCompaniesList.writeForm();

</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="update"}" enctype="multipart/form-data">

<h4>Редактирование сотрудника компании</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Компания:</TD>
  <TD><INPUT type="text" size="5" name="companies_id" id="companies_id" value="{$entry.companies_id}"> <INPUT type="button" onclick="objCompaniesList.showPopUp();" value="Выбрать"> <SPAN id="companies_id_name">{$entry.company_name}</SPAN></TD>
 </TR>
 <TR>
  <TD>Фотография:</TD>
  <TD>
   <INPUT type="file" name="logo">
   {if $entry.photo==1} <a href="/data/images/companies/{$entry.companies_id}/employers/{$entry.id}.jpg" target="_blank">Просмотреть</a>{/if}
  </TD>
 </TR>
 <TR>
  <TD>Имя:</TD>
  <TD><INPUT type="text" size="40" name="name" value="{$entry.name}"></TD>
 </TR>
 <TR>
  <TD>Фамилия:</TD>
  <TD><INPUT type="text" size="40" name="lastname" value="{$entry.lastname}"></TD>
 </TR>
 <TR>
  <TD>Email:</TD>
  <TD><INPUT type="text" size="40" name="email" value="{$entry.email}"></TD>
 </TR>
 <TR>
  <TD>Телефон:</TD>
  <TD><INPUT type="text" size="40" name="phone" value="{$entry.phone}"></TD>
 </TR>
 <TR>
  <TD>Должность:</TD>
  <TD><INPUT type="text" size="40" name="position" value="{$entry.position}"></TD>
 </TR>

 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Обновить"></TD></TR>
</TABLE>

</FORM>

<p><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></p>