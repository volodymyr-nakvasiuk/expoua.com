{include file="common/contentVisualEdit.tpl" textarea="description"}

<h4>Добавление нового участника</h4>

<FORM method="post" action="{getUrl add="1" action="insert"}" enctype="multipart/form-data">

<TABLE border="1" style="border-collapse:collapse" width="100%">
 <tr>
  <TD>Заголовок:</TD>
  <TD><INPUT type="text" size="30" name="name"></TD>
 </tr>
 <tr>
  <TD>Логотип:</TD>
  <TD><INPUT type="file" name="logo"></TD>
 </tr>
 <TR>
  <TD>Категория:</TD>
  <TD>
   <SELECT name="brands_categories_id">
   {foreach from=$list_categories.data item="el"}
    <OPTION value="{$el.id}">{$el.name}</OPTION>
   {/foreach}
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD>Город:</TD>
  <TD>
   <SELECT name="cities_id">
   {foreach from=$list_cities.data item="el"}
    <OPTION value="{$el.id}">{$el.name}</OPTION>
   {/foreach}
   </SELECT>
  </TD>
 </TR>
 <tr>
  <TD>Адрес:</TD>
  <TD><INPUT type="text" size="50" name="address"></TD>
 </tr>
 <tr>
  <TD>Индекс:</TD>
  <TD><INPUT type="text" size="30" name="postcode"></TD>
 </tr>
 <tr>
  <TD>Телефон:</TD>
  <TD><INPUT type="text" size="30" name="phone"></TD>
 </tr>
 <tr>
  <TD>Факс:</TD>
  <TD><INPUT type="text" size="30" name="fax"></TD>
 </tr>
 <tr>
  <TD>Email:</TD>
  <TD><INPUT type="text" size="30" name="email"></TD>
 </tr>
 <tr>
  <TD>Email запросов:</TD>
  <TD><INPUT type="text" size="30" name="email2"></TD>
 </tr>
 <tr>
  <TD>Адрес сайта:</TD>
  <TD><INPUT type="text" size="50" name="web_address"></TD>
 </tr>

 <TR>
  <TD colspan="2">
   Описание:<br />
   <TEXTAREA style="width:95%; height:450px;" name="description"></TEXTAREA>
  </TD>
 </TR>
 <TR><TD colspan="2" align="center"><INPUT type="submit" value="Добавить"></TD></TR>
</TABLE>

<P><a href="{getUrl add="1" action="list"}">Вернуться к списку</a></P>

</FORM>