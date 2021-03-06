{include file="common/contentVisualEdit.tpl" textarea="description" imagesDefaultParent="organizers"}

<SCRIPT type="text/javascript" language="javascript" src="{$document_root}js/adminListHelper.js"></SCRIPT>

<SCRIPT type="text/javascript" language="javascript">

objCitiesList = Shelby_Backend.ListHelper.cloneObject('objCitiesList');

objCitiesList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('country_name', 'Страна'));
objCitiesList.returnFieldId = 'cities_id';
objCitiesList.feedUrl = '{getUrl controller="admin_ep_locations_cities" action="list" feed="json"}';
objCitiesList.writeForm();

</SCRIPT>

<FORM method="post" action="{getUrl add="1" action="insert"}">

<h4>Добавляем организатора</h4>

<TABLE border="0" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="80" name="name"></TD>
 </TR>
 <TR>
  <TD>Город:</TD>
  <TD><INPUT type="text" size="5" name="cities_id" id="cities_id"> <INPUT type="button" onclick="objCitiesList.showPopUp();" value="Выбрать"> <SPAN id="cities_id_name"></SPAN></TD>
 </TR>
 <TR>
  <TD>Общественные<BR />организации:</TD>
  <TD>
   <DIV style="overflow:auto; height:250px;">
    {foreach from=$list_socorgs item="el"}
     <INPUT type="checkbox" name="so[{$el.id}]" value="{$el.id}" /> {$el.name}<BR />
    {/foreach}
   </DIV>
  </TD>
 </TR>

 <TR>
  <TD colspan="2">Описание:<BR />
   <TEXTAREA name="description" id="description" style="width:95%; height:500px;"></TEXTAREA>
  </TD>
 </TR>

 <TR>
  <TD colspan="2" valign="top">

   <div>Контакты:</div>
   <TABLE border="1" width="45%" style="border-collapse:collapse; float:left;">
    <TR>
     <TD>Адрес: </TD>
     <TD><INPUT type="text" name="address" size="25"></TD>
    </TR>
    <TR>
     <TD>Индекс: </TD>
     <TD><INPUT type="text" name="postcode" size="25"></TD>
    </TR>
    <TR>
     <TD>Email: </TD>
     <TD><INPUT type="text" name="email" size="25"></TD>
    </TR>
    <TR>
     <TD>Адрес сайта: </TD>
     <TD><INPUT type="text" name="web_address" size="25"></TD>
    </TR>
    <TR>
     <TD>Телефон: </TD>
     <TD><INPUT type="text" name="phone" size="25"></TD>
    </TR>
    <TR>
     <TD>Факс: </TD>
     <TD><INPUT type="text" name="fax" size="25"></TD>
    </TR>
   </TABLE>

   <TABLE border="1" width="45%" style="border-collapse:collapse; float:right;">
    <TR>
     <TD>Контактное лицо: </TD>
     <TD><INPUT type="text" name="cont_pers_name" size="25"></TD>
    </TR>
    <TR>
     <TD>Телефон КЛ: </TD>
     <TD><INPUT type="text" name="cont_pers_phone" size="25"></TD>
    </TR>
    <TR>
     <TD>Email КЛ: </TD>
     <TD><INPUT type="text" name="cont_pers_email" size="25"></TD>
    </TR>
   </TABLE>

  </TD>
 </TR>

 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Добавить"></TD></TR>
</TABLE>

</FORM>