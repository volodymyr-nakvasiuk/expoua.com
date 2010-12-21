{include file="common/contentVisualEdit.tpl" textarea="description" imagesDefaultParent="expocenters:`$entry.id`"}

<script type="text/javascript" src="http://www.google.com/jsapi?sensor=false&key=ABQIAAAAUTI8SejP98lLkXG0Y_J-cxTU9hzsxla95V8b0wW7MBgBbKbS4RQbf3FBrb87RFUhnRImhIonUrQKog"></script>
{*
<script type="text/javascript" src="http://www.google.com/jsapi?sensor=false&key=ABQIAAAAUTI8SejP98lLkXG0Y_J-cxTitACBWmJj_5p89hW_w8wSDJb8LhSMrKEXZp5hQ476dm73RfAM59DEnw"></script>
*}
<script type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></script>

<script>
var countryName = "{$entry.country_name}";
var cityName = "{$entry.city_name}";
{include file="controllers/admin_ep_expocenters/js_geo.tpl"}

objCitiesList = Shelby_Backend.ListHelper.cloneObject('objCitiesList');

objCitiesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('country_name', 'Страна'));
objCitiesList.returnFieldId = 'cities_id';
objCitiesList.feedUrl = '{getUrl controller="admin_ep_locations_cities" action="list" feed="json"}';
objCitiesList.writeForm();

objCitiesList.callbackUser = function(entry) {ldelim}
	countryName = entry.country_name;
	cityName = entry.name;
	showAddress($("#address").val() + ", " + cityName + ", " + countryName);
{rdelim}
</script>

{if $HMixed->isCountryOwned($entry.countries_id) == true}
 <form method="post" action="{getUrl add="1" action="update"}" enctype="multipart/form-data">
{/if}

<h4>Редактируем выставочный центр</h4>

<table border="0" width="100%" style="border-collapse:collapse;">
<tr valign="top">
  <td width="50%">
    <fieldset>
      <legend>Общая информация</legend>
    
      <table border="0" width="100%" style="border-collapse:collapse;">
      <tr>
        <td>Город:</td>
        <td>
         <input type="text" size="5" name="cities_id" id="cities_id" value="{$entry.cities_id}"> <input type="button" onclick="objCitiesList.showPopUp();" value="Выбрать"> <SPAN id="cities_id_name">{$entry.city_name}</SPAN>
        </td>
      </tr>
      
      <tr>
        <td>Название:</td>
        <td><input type="text" size="50" name="name" value="{$entry.name|escape:"html"}"></td>
      </tr>
      
      <tr>
        <td>Логотип:</td>
        <td>
         <input type="file" name="logo">
         {if $entry.logo==1} <a href="/data/images/expocenters/logo/{$entry.languages_id}/{$entry.id}.jpg" target="_blank">Просмотреть</a>{/if}
        </td>
      </tr>
      
      <tr>
        <td>Карта проезда:</td>
        <td>
         <input type="file" name="map">
         {if !empty($entry.image_map)} <a href="/data/images/expocenters/{$entry.id}/{$entry.image_map}" target="_blank">Просмотреть</a>{/if}
        </td>
      </tr>
      
      <tr>
        <td>План комплекса:</td>
        <td>
         <input type="file" name="plan">
         {if !empty($entry.image_plan)} <a href="/data/images/expocenters/{$entry.id}/{$entry.image_plan}" target="_blank">Просмотреть</a>{/if}
        </td>
      </tr>
      
      <tr>
        <td>Внешний вид:</td>
        <td>
         <input type="file" name="view">
         {if !empty($entry.image_view)} <a href="/data/images/expocenters/{$entry.id}/{$entry.image_view}" target="_blank">Просмотреть</a>{/if}
        </td>
      </tr>
      </table>  
    </fieldset>
  </td>
  
  <td>
    <fieldset>
      <legend>Доступ</legend>
      <table border="0" width="100%" style="border-collapse:collapse;">
      <tr>
        <td>Логин:</td>
        <td><input type="text" size="50" name="login" value="{$entry.login}"></td>
      </tr>
      <tr>
        <td>Пароль:</td>
        <td><input type="text" size="50" name="passwd" value="{$entry.passwd}"></td>
      </tr>
      </table>  
    </fieldset>
    <fieldset>
      <legend>Координаты</legend>
      <table border="0" width="100%" style="border-collapse:collapse;">
      <tr>
        <td>Широта:</td>
        <td><input type="text" size="50" id="latitude" name="latitude" value="{$entry.latitude}"></td>
      </tr>
      <tr>
        <td>Долгота:</td>
        <td><input type="text" size="50" id="longitude" name="longitude" value="{$entry.longitude}"></td>
      </tr>
      </table>  
    </fieldset>
  </td>
</tr>
</table>

<table border="0" width="100%" style="border-collapse:collapse;">
<tr>
  <td colspan="2">
    Описание:<br />
    <textarea name="description" id="description" 
              style="width:95%; height:500px;">{$entry.description|escape:"html"}</textarea>
  </td>
</tr>
</table>

<div id="map" style="width:100%; height:300px;"></div>

<table border="0" width="100%" style="border-collapse:collapse;">
<tr valign="top">
  <td width="50%">
    <fieldset>
      <legend>Контакты</legend>
    
      <table border="0" width="100%" style="border-collapse:collapse;">
      <tr>
        <td>Email: </td>
        <td><input type="text" name="email" value="{$entry.email}" size="40"></td>
      </tr>
      
      <tr>
        <td>Адрес сайта: </td>
        <td>
         <input type="text" name="web_address" value="{$entry.web_address}" size="40">
         (<a target="_blank" href="{$entry.web_address}">перейти</a>)
        </td>
      </tr>
      
      <tr>
        <td>Телефон: </td>
        <td><input type="text" name="phone" value="{$entry.phone}" size="40"></td>
      </tr>
      
      <tr>
        <td>Факс: </td>
        <td><input type="text" name="fax" value="{$entry.fax}" size="40"></td>
      </tr>
      
      <tr>
        <td>Адрес: </td>
        <td><input type="text" name="address" id="address" value="{$entry.address|escape:"html"}" size="40"></td>
      </tr>
      
      <tr>
        <td>Индекс: </td>
        <td><input type="text" name="postcode" value="{$entry.postcode}" size="40"></td>
      </tr>
      </table>
    </fieldset>
  </td>

  <td width="50%">
    <fieldset>
      <legend>Выставочные площади</legend>
    
      <table border="0" width="100%" style="border-collapse:collapse;">
      <tr>
        <td>Павильонов:</td>
        <td><input type="text" name="exhib_pav_num" size="10" value="{$entry.exhib_pav_num}"></td>
      </tr>
      
      <tr>
        <td>Общая выставочная площадь (брутто), в т.ч.:</td>
        <td><input type="text" name="s_total" size="10" value="{$entry.s_total}"></td>
      </tr>
      
      <tr>
        <td>Закрытая:</td>
        <td><input type="text" name="s_closed" size="10" value="{$entry.s_closed}"></td>
      </tr>
      
      <tr>
        <td>Открытая:</td>
        <td><input type="text" name="s_opened" size="10" value="{$entry.s_opened}"></td>
      </tr>
      
      <tr>
        <td>Возможная читая ВП (нетто), в т.ч.::</td>
        <td><input type="text" name="s_total_netto" size="10" value="{$entry.s_total_netto}"></td>
      </tr>
      
      <tr>
        <td>Закрытая:</td>
        <td><input type="text" name="s_closed_netto" size="10" value="{$entry.s_closed_netto}"></td>
      </tr>
      
      <tr>
        <td>Открытая:</td>
        <td><input type="text" name="s_opened_netto" size="10" value="{$entry.s_opened_netto}"></td>
      </tr>
      </table>
    </fieldset>
  </td>
</tr>

{if $HMixed->isCountryOwned($entry.countries_id) == true}
<tr>
  <td align="center" colspan="2"><input type="submit" value="Обновить"></td>
</tr>
</table>

</form>
{else}
</table>
{/if}

<hr />

<form id="loginForm" target="_blank" method="post" action="{getUrl controller='sab_venue_auth' action='login'}">
<input type="hidden" name="c" value="sab_venue_self" />
<input type="hidden" name="a" value="edit" />
<input type="hidden" name="login" value="{$entry.login}" />
<input type="hidden" name="passwd" value="{$entry.passwd}" />
<button type="submit"> Вход для выставочного центра </button>

</form>

<hr />

<p><A href="{getUrl add="1" action="list"}">Вернуться к списку</A></p>