<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>

objCompaniesList = Shelby_Backend.ListHelper.cloneObject('objCompaniesList');

objCompaniesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'));
objCompaniesList.returnFieldId = 'companies_id';
objCompaniesList.feedUrl = '{getUrl controller="admin_ep_companies_manage" action="list" feed="json"}';
objCompaniesList.writeForm();

</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="insert"}" enctype="multipart/form-data">

<h4>Добавляем нового сотрудника компании</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Компания:</TD>
  <TD><INPUT type="text" size="5" name="companies_id" id="companies_id"{if !empty($user_params.parent)} value="{$user_params.parent}"{/if}> <INPUT type="button" onclick="objCompaniesList.showPopUp();" value="Выбрать"> <SPAN id="companies_id_name"></SPAN></TD>
 </TR>
 <TR>
  <TD>Фотография:</TD>
  <TD><INPUT type="file" name="logo"></TD>
 </TR>
 <TR>
  <TD>Имя:</TD>
  <TD><INPUT type="text" size="40" name="name"></TD>
 </TR>
 <TR>
  <TD>Фамилия:</TD>
  <TD><INPUT type="text" size="40" name="lastname"></TD>
 </TR>
 <TR>
  <TD>Email:</TD>
  <TD><INPUT type="text" size="40" name="email"></TD>
 </TR>
 <TR>
  <TD>Телефон:</TD>
  <TD><INPUT type="text" size="40" name="phone"></TD>
 </TR>
 <TR>
  <TD>Должность:</TD>
  <TD><INPUT type="text" size="40" name="position"></TD>
 </TR>

 <TR>
  <TD colspan="2">
   <p>Скопировать запись во все языковые версии: <INPUT type="checkbox" name="_shelby_copy_all_langs" value="1" checked="checked"></p>
  </TD>
 </TR>
 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Добавить"></TD></TR>
</TABLE>

</FORM>

<p><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></p>