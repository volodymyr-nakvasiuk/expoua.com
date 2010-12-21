<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<h4>Редактирование категории</h4>

<SCRIPT type="text/javascript">
objCompaniesList = Shelby_Backend.ListHelper.cloneObject('objCompaniesList');

objCompaniesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'));
objCompaniesList.returnFieldId = 'companies_id';
objCompaniesList.feedUrl = '{getUrl controller="admin_ep_companies_manage" action="list" feed="json"}';
objCompaniesList.writeForm();
</SCRIPT>


<FORM action="{getUrl add="1" action="update"}" method="post">

<TABLE border="0" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="70" name="name" value="{$entry.name}"></TD>
 </TR>

<TR>
  <TD>Компания:</TD>
  <TD><INPUT type="text" size="5" name="companies_id" id="companies_id" value="{$entry.companies_id}"> <INPUT type="button" onclick="objCompaniesList.showPopUp();" value="Выбрать"> <SPAN id="companies_id_name">{$entry.company_name}</SPAN></TD>
 </TR>

<TR>
  <TD>&nbsp;</TD>
  <TD><INPUT type="submit" value="Сохранить" /></TD>
 </TR>

</TABLE>
</FORM>
