{include file="common/contentVisualEdit.tpl" textarea="additional_info" imagesDefaultParent="service_companies:`$entry.id`"}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>
objSocOrgsList = Shelby_Backend.ListHelper.cloneObject('objSocOrgsList');
objCitiesList = Shelby_Backend.ListHelper.cloneObject('objCitiesList');

objSocOrgsList.columns = new Array(new Array('id', 'Id'), new Array('name', 'Название'), new Array('city_name', 'Город'));
objSocOrgsList.returnFieldId = 'social_organizations_id';
objSocOrgsList.feedUrl = '{getUrl controller="admin_ep_socorgs" action="list" feed="json"}';
objSocOrgsList.writeForm();

objCitiesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('country_name', 'Страна'));
objCitiesList.returnFieldId = 'cities_id';
objCitiesList.feedUrl = '{getUrl controller="admin_ep_locations_cities" action="list" feed="json"}';
objCitiesList.writeForm();
</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="update"}" enctype="multipart/form-data">

<h4>Редактируем сервисную компанию</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Общественная организация:</TD>
  <TD><INPUT type="text" size="5" name="social_organizations_id" id="social_organizations_id" value="{$entry.social_organizations_id}"> <INPUT type="button" onclick="objSocOrgsList.showPopUp();" value="Выбрать"> <SPAN id="social_organizations_id_name">{$entry.social_organization_name}</SPAN></TD>
 </TR>
 <TR>
  <TD>Категория:</TD>
  <TD>
   <SELECT name="service_companies_categories_id">
    {foreach from=$list_service_companies_cats item="el"}
     <OPTION value="{$el.id}"{if $entry.service_companies_categories_id==$el.id} selected{/if}>{$el.name}</OPTION>
    {/foreach}
   </SELECT>
   Позиция: <INPUT type="text" size="5" name="sort_order_cat" value="{$entry.sort_order_cat/2}" /> &nbsp; 0 - в конец списка
 </TR>
 <TR>
  <TD>Город:</TD>
  <TD>
   <INPUT type="text" size="5" name="cities_id" id="cities_id" value="{$entry.cities_id}"> <INPUT type="button" onclick="objCitiesList.showPopUp();" value="Выбрать"> <SPAN id="cities_id_name">{$entry.city_name}</SPAN>
  </TD>
 </TR>
 <TR>
  <TD>Позиция в списке:</TD>
  <TD><INPUT type="text" size="5" name="sort_order" value="{$entry.sort_order/2}"> &nbsp; 0 - в конец списка</TD>
 </TR>
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="50" name="name" value="{$entry.name}"></TD>
 </TR>
 <TR>
  <TD>Логотип:</TD>
  <TD>
   <INPUT type="file" name="logo">
   {if $entry.logo==1} <a href="/data/images/service_companies/logo/{$entry.languages_id}/{$entry.id}.jpg" target="_blank">Просмотреть</a>{/if}
  </TD>
 </TR>
 <TR>
  <TD>Email для запросов:</TD>
  <TD><INPUT type="text" size="50" name="email_requests" value="{$entry.email_requests}"></TD>
 </TR>
 <TR>
  <TD colspan="2">Описание:<BR />
   <TEXTAREA name="content" style="width:95%; height:50px;">{$entry.description}</TEXTAREA>
  </TD>
 </TR>
 <TR>
  <TD colspan="2">Виды деятельности:<BR />
   <TEXTAREA name="activity_forms" style="width:95%; height:50px;">{$entry.activity_forms}</TEXTAREA>
  </TD>
 </TR>
 <TR>
  <TD colspan="2">Дополнительная информация:<BR />
   <TEXTAREA name="additional_info" id="additional_info" style="width:95%; height:500px;">{$entry.additional_info|escape:"html"}</TEXTAREA>
  </TD>
 </TR>

 <TR>
  <TD colspan="2" valign="top">

   <div>Контакты:</div>
   <TABLE border="1" width="45%" style="border-collapse:collapse; float:left;">
    <TR>
     <TD>Email: </TD>
     <TD><INPUT type="text" name="email" value="{$entry.email}" size="25"></TD>
    </TR>
    <TR>
     <TD>Адрес сайта: </TD>
     <TD><INPUT type="text" name="web_address" value="{$entry.web_address}" size="25"></TD>
    </TR>
    <TR>
     <TD>Телефон: </TD>
     <TD><INPUT type="text" name="phone" value="{$entry.phone}" size="25"></TD>
    </TR>
    <TR>
     <TD>Факс: </TD>
     <TD><INPUT type="text" name="fax" value="{$entry.fax}" size="25"></TD>
    </TR>
   </TABLE>

   <TABLE border="1" width="45%" style="border-collapse:collapse; float:right;">
    <TR>
     <TD>Адрес: </TD>
     <TD><INPUT type="text" name="address" value="{$entry.address}" size="25"></TD>
    </TR>
    <TR>
     <TD>Индекс: </TD>
     <TD><INPUT type="text" name="postcode" value="{$entry.postcode}" size="25"></TD>
    </TR>
   </TABLE>

  </TD>
 </TR>

 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Обновить"></TD></TR>
</TABLE>

</FORM>