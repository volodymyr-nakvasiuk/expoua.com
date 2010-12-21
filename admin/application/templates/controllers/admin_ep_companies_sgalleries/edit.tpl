<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>

objServicesList = Shelby_Backend.ListHelper.cloneObject('objServicesList');

objServicesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('company_name', 'Компания'));
objServicesList.returnFieldId = 'companies_services_id';
objServicesList.feedUrl = '{getUrl controller="admin_ep_companies_services" action="list" feed="json"}';
objServicesList.writeForm();

</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="update"}" enctype="multipart/form-data">

<h4>Редактируем изображение в галерее товаров и услуг</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Услуга/товар:</TD>
  <TD><INPUT type="text" size="5" name="companies_services_id" id="companies_services_id" value="{$entry.companies_services_id}" /> <INPUT type="button" onclick="objServicesList.showPopUp();" value="Выбрать"> <SPAN id="companies_services_id_name">{$entry.service_name}</SPAN></TD>
 </TR>
 <TR>
  <TD>Изображение:</TD>
  <TD><INPUT type="file" name="logo"> <a href="/data/images/companies/{$entry.companies_id}/galleries/{$entry.companies_services_id}/{$entry.id}_big.jpg" target="_blank">Просмотреть</a></TD>
 </TR>
 <TR>
  <TD>Заголовок:</TD>
  <TD><INPUT type="text" size="50" name="title" value="{$entry.title}"></TD>
 </TR>
 <TR>
  <TD colspan="2">Описание:<BR />
  <TEXTAREA name="description" style="width:90%; height:100px;">{$entry.description|escape:"html"}</TEXTAREA>
 </TR>

 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Изменить"></TD></TR>
</TABLE>

</FORM>

<p><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></p>