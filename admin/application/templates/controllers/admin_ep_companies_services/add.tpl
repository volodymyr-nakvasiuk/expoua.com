{include file="common/contentVisualEdit.tpl" textarea="content"}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>

objCompaniesList = Shelby_Backend.ListHelper.cloneObject('objCompaniesList');

objCompaniesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'));
objCompaniesList.returnFieldId = 'companies_id';
objCompaniesList.feedUrl = '{getUrl controller="admin_ep_companies_manage" action="list" feed="json"}';
objCompaniesList.writeForm();

{literal}
objCompaniesList.callbackUser = function(entry) {
	createCategoriesList(entry.id);
}

function createCategoriesList(id) {
	var url = '{/literal}{getUrl controller="admin_ep_companies_servicescats" results="999" action="list" feed="json"}{literal}parent/' + id + '/';

	$.getJSON(url, function(json) {
		var tmp = '<SELECT name="companies_services_cats_id"><option value="">(Не установлено)</option>';
		$.each(json.list.data, function(i) {
			tmp += '<option value="' + json.list.data[i].id + '">' + json.list.data[i].name + '</option>';
		});
		tmp += "</SELECT>";
		$("#companies_services_cats_id").html(tmp);
	});
}
{/literal}
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="insert"}" enctype="multipart/form-data">

<h4>Добавляем новый товар или услугу компании</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="70" name="name"></TD>
 </TR>
 <TR>
  <TD>Компания:</TD>
  <TD><INPUT type="text" size="5" name="companies_id" id="companies_id"{if !empty($user_params.parent)} value="{$user_params.parent}"{/if}> <INPUT type="button" onclick="objCompaniesList.showPopUp();" value="Выбрать"> <SPAN id="companies_id_name"></SPAN></TD>
 </TR>
 <TR>
  <TD>Изображение:</TD>
  <TD><INPUT type="file" name="logo"></TD>
 </TR>
 <TR>
  <TD>Категория:</TD>
  <TD id="companies_services_cats_id">(Выберите компанию сперва)</TD>
 </TR>
 <TR>
  <TD>Тип:</TD>
  <TD>
   <SELECT name="type">
    <OPTION value="product">Продукт</OPTION>
    <OPTION value="service">Услуга</OPTION>
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD>Цена:</TD>
  <TD><INPUT type="text" size="12" name="price"> (Формат ######.##)</TD>
 </TR>
 <TR>
  <TD colspan="2">Краткое описание:<BR />
   <TEXTAREA name="short" style="width:95%; height:100px;"></TEXTAREA>
  </TD>
 </TR>
 <TR>
  <TD colspan="2">Полное описание:<BR />
   <TEXTAREA name="content" id="content" style="width:95%; height:500px;"></TEXTAREA>

   <p>Скопировать запись во все языковые версии: <INPUT type="checkbox" name="_shelby_copy_all_langs" value="1" checked="checked"></p>
  </TD>
 </TR>
 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Добавить"></TD></TR>
</TABLE>

</FORM>

<p><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></p>