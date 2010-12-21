{include file="common/contentVisualEdit.tpl" textarea="description" imagesDefaultParent="expocenters"}

<script type="text/javascript" src="http://www.google.com/jsapi?sensor=false&key=ABQIAAAAUTI8SejP98lLkXG0Y_J-cxRXNO8L_DSf4wwKbhLlwqtfbkm9cBRyC-4Xc4-i3q_UTuY0NsN88fJjkQ"></script>
{*
<script type="text/javascript" src="http://www.google.com/jsapi?sensor=false&key=ABQIAAAAUTI8SejP98lLkXG0Y_J-cxTitACBWmJj_5p89hW_w8wSDJb8LhSMrKEXZp5hQ476dm73RfAM59DEnw"></script>
*}
<script type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></script>

<script type="text/javascript">
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
	if ($("#address").val() != "") {ldelim}
		showAddress($("#address").val() + ", " + cityName + ", " + countryName);
	{rdelim}
{rdelim}
</script>


<form method="post" action="{getUrl add="1" action="insert"}" enctype="multipart/form-data">

<h4>Добавляем выставочный центр</h4>

<table border="0" width="100%" style="border-collapse:collapse;">
<tr valign="top">
  <td width="50%">
    <fieldset>
      <legend>Общая информация</legend>
    
      <table border="0" width="100%" style="border-collapse:collapse;">
      <tr>
        <td>Город:</td>
        <td>
         <input type="text" size="5" name="cities_id" id="cities_id" value="">
         <input type="button" onclick="objCitiesList.showPopUp();" value="Выбрать">
         <SPAN id="cities_id_name"></SPAN>
        </td>
      </tr>
      
      <tr>
        <td>Название:</td>
        <td><input type="text" size="50" name="name" value=""></td>
      </tr>
      
      <tr>
        <td>Логотип:</td>
        <td>
         <input type="file" name="logo">
        </td>
      </tr>
      
      <tr>
        <td>Карта проезда:</td>
        <td>
         <input type="file" name="map">
        </td>
      </tr>
      
      <tr>
        <td>План комплекса:</td>
        <td>
         <input type="file" name="plan">
        </td>
      </tr>
      
      <tr>
        <td>Внешний вид:</td>
        <td>
         <input type="file" name="view">
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
        <td><input type="text" size="50" name="login" value=""></td>
      </tr>
      
      <tr>
        <td>Пароль:</td>
        <td><input type="text" size="50" name="passwd" value=""></td>
      </tr>
      </table>  
    </fieldset>
    <fieldset>
      <legend>Координаты</legend>
      <table border="0" width="100%" style="border-collapse:collapse;">
      <tr>
        <td>Широта:</td>
        <td><input type="text" size="50" id="latitude" name="latitude" value="0"></td>
      </tr>
      <tr>
        <td>Долгота:</td>
        <td><input type="text" size="50" id="longitude" name="longitude" value="0"></td>
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
    <textarea name="description" id="description" style="width:95%; height:500px;"></textarea>
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
        <td><input type="text" name="email" value="" size="50"></td>
      </tr>
      
      <tr>
        <td>Адрес сайта: </td>
        <td><input type="text" name="web_address" value="" size="50"></td>
      </tr>
      
      <tr>
        <td>Телефон: </td>
        <td><input type="text" name="phone" value="" size="50"></td>
      </tr>
      
      <tr>
        <td>Факс: </td>
        <td><input type="text" name="fax" value="" size="50"></td>
      </tr>
      
      <tr>
        <td>Адрес: </td>
        <td><input type="text" name="address" id="address" value="" size="50"></td>
      </tr>
      
      <tr>
        <td>Индекс: </td>
        <td><input type="text" name="postcode" value="" size="50"></td>
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
        <td><input type="text" name="exhib_pav_num" size="10" value=""></td>
      </tr>
      
      <tr>
        <td>Общая выставочная площадь (брутто), в т.ч.:</td>
        <td><input type="text" name="s_total" size="10" value=""></td>
      </tr>
      
      <tr>
        <td>Закрытая:</td>
        <td><input type="text" name="s_closed" size="10" value=""></td>
      </tr>
      
      <tr>
        <td>Открытая:</td>
        <td><input type="text" name="s_opened" size="10" value=""></td>
      </tr>
      
      <tr>
        <td>Возможная читая ВП (нетто), в т.ч.::</td>
        <td><input type="text" name="s_total_netto" size="10" value=""></td>
      </tr>
      
      <tr>
        <td>Закрытая:</td>
        <td><input type="text" name="s_closed_netto" size="10" value=""></td>
      </tr>
      
      <tr>
        <td>Открытая:</td>
        <td><input type="text" name="s_opened_netto" size="10" value=""></td>
      </tr>
      </table>
    </fieldset>
  </td>
</tr>

<tr>
  <td align="center" colspan="2">
    <input type="submit" value=" Добавить ">
  </td>
</tr>
</table>

</form>

<p><A href="{getUrl add="1" action="list"}">Вернуться к списку</A></p>