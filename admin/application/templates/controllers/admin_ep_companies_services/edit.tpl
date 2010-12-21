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
			tmp += '<option value="' + json.list.data[i].id + '"';

			if (json.list.data[i].id==parseInt({/literal}{$entry.companies_services_cats_id}{literal})) {
				tmp += ' selected="selected"';
			}

			tmp += '>' + json.list.data[i].name + '</option>';

		});
		tmp += "</SELECT>";
		$("#companies_services_cats_id").html(tmp);
	});
}
{/literal}

$(document).ready(function(){ldelim}
	createCategoriesList({$entry.companies_id});
{rdelim});
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="update"}" enctype="multipart/form-data">

<h4>Редактируем товар или услугу компании</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="70" name="name" value="{$entry.name}"></TD>
 </TR>
 <TR>
  <TD>Компания:</TD>
  <TD><INPUT type="text" size="5" name="companies_id" id="companies_id" value="{$entry.companies_id}"> <INPUT type="button" onclick="objCompaniesList.showPopUp();" value="Выбрать"> <SPAN id="companies_id_name">{$entry.company_name}</SPAN></TD>
 </TR>
 <TR>
  <TD>Изображение:</TD>
  <TD>
   <INPUT type="file" name="logo">
   {if $entry.photo==1} <a href="/data/images/companies/{$entry.companies_id}/services/logo/{$entry.id}_big.jpg" target="_blank">Просмотреть</a>{/if}
  </TD>
 </TR>
 <TR>
  <TD>Категория:</TD>
  <TD id="companies_services_cats_id"></TD>
 </TR>
 <TR>
  <TD>Тип:</TD>
  <TD>
   <SELECT name="type">
    <OPTION value="product" {if $entry.type == 'product'}selected="selected"{/if}>Продукт</OPTION>
    <OPTION value="service" {if $entry.type == 'service'}selected="selected"{/if}>Услуга</OPTION>
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD>Цена:</TD>
  <TD><INPUT type="text" size="12" name="price" value="{$entry.price}"> (Формат ######.##)</TD>
 </TR>
 <TR>
  <TD colspan="2">Краткое описание:<BR />
   <TEXTAREA name="short" style="width:95%; height:100px;">{$entry.short}</TEXTAREA>
  </TD>
 </TR>
 <TR>
  <TD colspan="2">Полное описание:<BR />
   <TEXTAREA name="content" id="content" style="width:95%; height:500px;">{$entry.content}</TEXTAREA>
  </TD>
 </TR>
 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Сохранить"></TD></TR>
</TABLE>

</FORM>

<p><a href="{getUrl add="1" action="list" del="id"}">Вернуться к списку</a></p>