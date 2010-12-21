{include file="common/contentVisualEdit.tpl" textarea="description" imagesDefaultParent="social_organizations:`$entry.id`"}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT>
objCitiesList = Shelby_Backend.ListHelper.cloneObject('objCitiesList');

objCitiesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('country_name', 'Страна'));
objCitiesList.returnFieldId = 'cities_id';
objCitiesList.feedUrl = '{getUrl controller="admin_ep_locations_cities" action="list" feed="json"}';
objCitiesList.writeForm();
</SCRIPT>

<h4>Просматриваем общественную организацию</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Город:</TD>
  <TD>
   <INPUT type="text" size="5" name="cities_id" id="cities_id" value="{$entry.cities_id}"> <INPUT type="button" onclick="objCitiesList.showPopUp();" value="Выбрать"> <SPAN id="cities_id_name">{$entry.city_name}</SPAN>
  </TD>
 </TR>
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="50" name="name" value="{$entry.name}"></TD>
 </TR>
 <TR>
  <TD>Логотип:</TD>
  <TD>
   <INPUT type="file" name="logo">
   {if $entry.logo==1} <a href="/data/images/social_organizations/logo/{$entry.languages_id}/{$entry.id}.jpg" target="_blank">Просмотреть</a>{/if}
  </TD>
 </TR>
 <TR>
  <TD colspan="2">Дополнительная информация:<BR />
   <TEXTAREA name="description" id="description" style="width:95%; height:500px;">{$entry.description|escape:"html"}</TEXTAREA>
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
</TABLE>